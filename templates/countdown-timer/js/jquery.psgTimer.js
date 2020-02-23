(function () {
    var callbacks = {
        onInit: function () {
        },
        onStart: function () {
        },
        onStop: function () {
        },
        onEnd: function () {
        },
        onChangeStart: function () {
        },
        onChangeEnd: function () {
        }
    };

    var base = {
        stopped: true,
        timezone: 0,
        diff: null,
        isEnd: false
    };

    var PsgTimer = function (selector, options) {
        var timer = this;
        if (selector.nodeType === Node.ELEMENT_NODE) {
            timer.container = $(selector);
        } else if (typeof selector === 'string') {
            timer.selector = selector;
            timer.container = $(timer.selector);
        } else if (typeof selector === 'object') {
            options = selector;
            timer.selector = options.selector;
            timer.container = $(timer.selector);
        }

        timer.options = $.extend({}, {
            selector: '#psgTimer',
            animation: false,
            multipleBlocks: false,
            endDateTime: undefined,
            // currentDateTime: window.serverTime['U'] * 1000 || Date.now(),
            currentDateTime: Date.now(),
            labels: {
                days:     timer.container.attr('data-label-days')    ? timer.container.attr('data-label-days') : false,
                hours:    timer.container.attr('data-label-hours')   ? timer.container.attr('data-label-hours') : false,
                minutes:  timer.container.attr('data-label-minutes') ? timer.container.attr('data-label-minutes') : false,
                seconds:  timer.container.attr('data-label-seconds') ? timer.container.attr('data-label-seconds') : false
            }
        }, options);

        timer.callbacks = timer.options.callbacks = $.extend({}, callbacks, timer.options.callbacks);
        timer.base = $.extend({}, base);

        if (typeof timer.options.endDateTime === 'string') {
            timer.options.endDateTime = setTimerEndFromString(timer, timer.options.endDateTime);
        }

        timer.container.length ? timer.init() : console.log('No timer element on this page');
    };

    PsgTimer.prototype.init = function () {
        var timer = this,
            options = this.options;

        var timerEnd = timer.container.attr('data-timer-end');

        if (timerEnd !== undefined) {
            options.endDateTime = setTimerEndFromString(timer, timerEnd);
        }

        // options.endDateTime = options.endDateTime + (timer.base.timezone * 1000 * 60 * 60);

        timer.countdown = transformCountToArray(getCurrentCountDown(timer), options.multilpeBlocks);

        timer.container.addClass('psgTimer').append(createMarkup(timer));
        if (options.animation) {
            timer.container.addClass('psgTimer_' + options.animation);
        }

        timer.query = setQueries(timer);
        timer.callbacks.onInit();

        if (!timer.base.isEnd) {
            timer.start();
        }
    };

    PsgTimer.prototype.start = function () {
        var timer = this;

        if (timer.base.stopped) {
            timer.base.stopped = false;

            timer.intervalId = setInterval(function () {
                updateCounter(timer);
            }, 1000);

            timer.callbacks.onStart();
        }
    };

    PsgTimer.prototype.restart = function () {
        var timer = this;
        timer.options.currentDateTime = Date.now();
        timer.start();
    };

    PsgTimer.prototype.stop = function () {
        var timer = this;
        timer.base.stopped = true;
        clearTimeout(timer.intervalId);

        timer.callbacks.onStop();
    };


    var getCurrentCountDown = function (timer) {
        var options = timer.options;
        var base = timer.base;

        options.currentDateTime = options.currentDateTime + 1001;
        base.diff = options.endDateTime - options.currentDateTime;

        var seconds = 0;
        var minutes = 0;
        var hours = 0;
        var days = 0;

        if (base.diff > 0) {
            var total = parseFloat(((((base.diff / 1000.0) / 60.0) / 60.0) / 24.0));
            days = parseInt(total);
            total -= days;
            total *= 24.0;
            hours = parseInt(total);
            total -= hours;
            total *= 60.0;
            minutes = parseInt(total);
            total -= minutes;
            total *= 60;
            seconds = parseInt(total);
        } else {
            timer.callbacks.onEnd();
            timer.stop();
            timer.base.isEnd = true;
        }

        return {
            days: {
                amount: days,
                max: Infinity,
                className: 'days'
            },
            hours: {
                amount: hours,
                max: 24,
                className: 'hours'
            },
            minutes: {
                amount: minutes,
                max: 60,
                className: 'minutes'
            },
            seconds: {
                amount: seconds,
                max: 60,
                className: 'seconds'
            }
        }
    };

    var transformCountToArray = function (count, multilpeBlocks) {
        if (typeof count === 'object') {
            for (var unit in count) {
                if (count.hasOwnProperty(unit)) {
                    count[unit].amount = count[unit].amount.toString();

                    if (count[unit].amount.length < 2) {
                        count[unit].amount = '0' + count[unit].amount;
                    }

                    if (multilpeBlocks) {
                        count[unit].amount = count[unit].amount.split('');
                    } else {
                        count[unit].amount = [count[unit].amount];
                    }
                }
            }
        }

        return count;
    };

    var getTimeZone = function (string) {
        var hours, minutes;
        var number = string.replace(/\D/g, '');
        var digit = string.replace(/[^+-]/g, '');
        var multiplier = digit === '-' ? (-1) : 1;

        if (number.length >= 3) {
            hours = Number(number.substr(0, number.length - 2));
            minutes = Number(number.substr(number.length - 2, 2));
        } else {
            hours = Number(number);
            minutes = 0;
        }

        return (hours + minutes/60) * multiplier;
    };

    var setTimerEndFromString = function (timer, endTimeString) {
        var timerDate = {};
        var timerEnd = endTimeString.split(' ');
        var endTime;

        var timeExp = /^([0-1]\d|2[0-3])(:[0-5]\d){1,2}$/;
        var dateExp = /(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d/;
        var zoneExp = /(UTC|GMT)[+-](\d{1,2}([:,.]?\d{2})?)/;

        for (var i = 0; i < timerEnd.length; i++) {
            if (timerEnd[i].match(timeExp)) {
                timerDate.time = timerEnd[i].split(':');
            } else if (timerEnd[i].match(dateExp)) {
                timerDate.date = timerEnd[i].split('.');
            } else if (timerEnd[i].match(zoneExp)) {
                timer.base.timezone = getTimeZone(timerEnd[i]);
            } else {
                console.log('Wrong end time.');
            }
        }

        timerDate.year = parseInt(timerDate.date[2]) || 0;
        timerDate.month = parseInt(timerDate.date[1]) - 1 || 0;
        timerDate.day = parseInt(timerDate.date[0]) || 0;
        timerDate.hours = parseInt(timerDate.time[0]) || 0;
        timerDate.minutes = parseInt(timerDate.time[1]) || 0;
        timerDate.seconds = parseInt(timerDate.time[2]) || 0;
        timerDate.miliseconds = parseInt(timerDate.time[3]) || 0;

        endTime = Date.UTC(timerDate.year, timerDate.month, timerDate.day, timerDate.hours, timerDate.minutes, timerDate.seconds, timerDate.miliseconds);

        return endTime;
    };

    var createMarkup = function (timer) {
        var countdown = timer.countdown;
        var markup = {};

        for (var unit in countdown) {
            if (countdown.hasOwnProperty(unit)) {
                var numberBlocks = '';
                countdown[unit].amount.forEach(function (num) {
                    numberBlocks += numberContainer(timer, num);
                });

                markup.unit += '<div class="' + countdown[unit].className + ' psgTimer_unit">' + numberBlocks + '</div>';
            }
        }

        markup.numbers = '<div class="psgTimer_numbers">' + markup.unit + '</div>';
        markup.full = markup.numbers;

        if (
            timer.options.labels &&
            timer.options.labels.days &&
            timer.options.labels.hours &&
            timer.options.labels.minutes &&
            timer.options.labels.seconds
        ) {
            var labels = timer.options.labels;
            markup.labels = '<div class="psgTimer_labels">' +
                '<div class="days">' + labels.days + '</div>' +
                '<div class="hours">' + labels.hours + '</div>' +
                '<div class="minutes">' + labels.minutes + '</div>' +
                '<div class="seconds">' + labels.seconds + '</div>' +
                '</div>';
            markup.full = markup.numbers + markup.labels;
        } else {

            markup.full = markup.numbers;
        }

        return markup.full;
    };

    var numberContainer = function (timer, num) {
        var markup = '',
            data = 'data-number="' + num + '"';

        var numberBlock = '<div class="number" ' + data + '>' + num + '</div>';

        if (timer.options.animation === 'fade') {
            markup = '<div>' + numberBlock + '</div>';
        } else {
            markup = numberBlock;
        }

        return markup;
    };

    var setQueries = function (timer) {
        var countdown = timer.countdown,
            query = {};

        for (var unit in countdown) {
            if (countdown.hasOwnProperty(unit)) {
                query[unit] = timer.container.find(numberSelector(timer, countdown[unit].className));
            }
        }

        return query;
    };

    var numberSelector = function (timer, className) {
        var selector = '';

        if (timer.options.animation === 'fade') {
            selector = '.' + className + ' .number';
        } else {
            selector = '.' + className + ' .number';
        }

        return selector;
    };

    var updateCounter = function (timer) {
        timer.callbacks.onChangeStart();

        timer.countdown = transformCountToArray(getCurrentCountDown(timer), timer.options.multilpeBlocks);

        for (var unit in timer.countdown) {
            if (timer.countdown.hasOwnProperty(unit)) {
                timer.countdown[unit].amount.forEach(function (number, index) {
                    if (timer.query[unit][index].getAttribute('data-number') !== number) {
                        aminate(timer.query[unit][index], number, timer.options.animation);
                    }
                });
            }
        }

        timer.callbacks.onChangeEnd();
    };

    var aminate = function (el, value, animationType) {
        var $el = $(el);
        $el.attr('data-number', value);

        if (animationType === 'fade') {
            animation.fade($el, value);
        } else {
            $el.html(value);
        }
    };

    var animation = {
        fade: function ($el, value) {
            var animDuration = 350;

            $el.css({
               'transition': 'opacity ' + animDuration + 'ms',
                'opacity': '0'
            });

            setTimeout(function () {
                $el.html(value).css('opacity', 1);
            }, animDuration + 10);
        }
    };

    window.PsgTimer = PsgTimer;
})();
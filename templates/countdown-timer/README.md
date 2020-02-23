# PsgTimer

### HTML
```HTML
<div id="psgTimer"></div>
```
### JavaScript
```JavaScript
var timer = new PsgTimer('#psgTimer', {
    // options
});
```

## Options
### selector

Unique element selector of a timer (id is recommended). Default value - "#psgTimer". Can be passed as first parameter in initialization.
```JavaScript
var timer = new PsgTimer({
    selector: '#psgTimer'
});

var timer = new PsgTimer('#psgTimer', {
    // options
});
```

### multipleBlocks
(boolean) Shows each number in different blocks. Default value - "false".
### endDateTime
(string/number) Timer's end date. Default value - 'undefined'.
Can be string with the date or instance of new Date().
```JavaScript
var timer = new PsgTimer({
    endDateTime: '13.06.2019 13:00:00'
});
```
Same string can be passed as an attribute "data-timer-end" (has higher priority).
```HTML
<div id="psgTimer" data-timer-end="13.06.2018 13:00:00"></div>
```
### currentDateTime
(number) Timer's start date. Default value - 'new Date()' (set current date). Takes instance of new Date().
### labels
(object) Timer's labels. Default values:
```JavaScript
var timer = new PsgTimer({
    labels: {
        days: 'Days',
        hours: 'Hours',
        minutes: 'minutes',
        seconds: 'seconds'
    }
});
```
Can be passed via HTML attributes:
```HTML
<div id="psgTimer"
    data-label-days="Days"
    data-label-hours="Hours"
    data-label-minutes="Minutes"
    data-label-seconds="Seconds"
></div>
```

## Callbacks

### onInit
Runs on init.
### onStart
Runs on start of countdown.
### onStop
Runs on timer stop.
### onEnd
Runs on end of countdown.
### onChangeStart 
Runs before change of a number.
### onChangeEnd
Runs after change of a number.

```JavaScript
var timer = new PsgTimer({
    callbacks: {
        onInit: function() {
          console.log('Hello world!');
        }
    }
});
```

### Methods

### timer.start()
Starts timer.
### timer.stop()
Stops timer.
```JavaScript
var timer = new PsgTimer("#psgTimer");
timer.stop();
```
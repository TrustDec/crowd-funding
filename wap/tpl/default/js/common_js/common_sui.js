/*
 * 重新定义   时间日期选择器  
 * 函数 名称   datetimePickers 
 * 时间格式   yyyy-mm-dd
 */

+ function($) {
  "use strict";

  var today = new Date();

  var getDays = function(max) {
    var days = [];
    for(var i=1; i<= (max||31);i++) {
      days.push(i < 10 ? "0"+i : i);
    }
    return days;
  };

  var getDaysByMonthAndYear = function(month, year) {
    var int_d = new Date(year, parseInt(month)+1-1, 1);
    var d = new Date(int_d - 1);
    return getDays(d.getDate());
  };

  var formatNumber = function (n) {
    return n < 10 ? "0" + n : n;
  };

  var initMonthes = ('01 02 03 04 05 06 07 08 09 10 11 12').split(' ');

  var initYears = (function () {
    var arr = [];
    for (var i = 1950; i <= 2030; i++) { arr.push(i); }
    return arr;
  })();


  var defaults = {

    rotateEffect: false,  //为了性能

	value: [today.getFullYear(), formatNumber(today.getMonth()+1), today.getDate()],

    onChange: function (picker, values, displayValues) {
      var days = getDaysByMonthAndYear(picker.cols[1].value, picker.cols[0].value);
      var currentValue = picker.cols[2].value;
      if(currentValue > days.length) currentValue = days.length;
      picker.cols[2].setValue(currentValue);
    },

    formatValue: function (p, values, displayValues) {
	  return displayValues[0] + '-' + values[1] + '-' + values[2];
    },

    cols: [
      // Years
      {
        values: initYears
      },
      // Months
      {
        values: initMonthes
      },
      // Days
      {
        values: getDays()
      },

      // Space divider
      {
        divider: true,
        content: '  '
      },
    ]
  };
   
  $.fn.datetimePickers = function(params) {
    return this.each(function() {
      if(!this) return;
      var p = $.extend(defaults, params);
      $(this).picker(p);
    });
  };
}(Zepto);

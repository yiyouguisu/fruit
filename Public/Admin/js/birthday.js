; (function ($) {
    $.fn.birthday = function (options) {
        var opts = $.extend({}, $.fn.birthday.defaults, options);//整合参数
        var $year = $(this).children("select[name=" + opts.year + "]");
        var $month = $(this).children("select[name=" + opts.month + "]");
        var $day = $(this).children("select[name=" + opts.day + "]");
        MonHead = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        return this.each(function () {
            var y = new Date().getFullYear();
            var con = "<option value=''>--年--</option>";
            //添加年份
            for (i = y; i >= (y - 100) ; i--) {
                con += "<option value='" + i + "'>" + i + "年</option>";
            }
            $year.append(con);
            var _y = $(this).attr("data-year");
            if (_y != "") {
                $year.val(_y);
            }
            con = "<option value=''>--月--</option>";
            //添加月份
            for (i = 1; i <= 12; i++) {
                con += "<option value='" + i + "'>" + i + "月</option>";
            }
            $month.append(con);

            var _m = $(this).attr("data-month");
            if (_m != "") {
                $month.val(_m);
            } else {
                _m = 1;
            }
            con = "<option value=''>--日--</option>";
            //添加日期
            var n = MonHead[_m - 1];//默认显示第一月
            for (i = 1; i <= n; i++) {
                con += "<option value='" + i + "'>" + i + "日</option>";
            }
            $day.append(con);
            var _d = $(this).attr("data-day");
            if (_d != "") {
                $day.val(_d);
            }

            $.fn.birthday.change($(this));

        });
    };
    $.fn.birthday.change = function (obj) {
        obj.children("select[name=" + $.fn.birthday.defaults.year + "],select[name=" + $.fn.birthday.defaults.month + "]").change(function () {
            var $year = obj.children("select[name=" + $.fn.birthday.defaults.year + "]");
            var $month = obj.children("select[name=" + $.fn.birthday.defaults.month + "]");
            var $day = obj.children("select[name=" + $.fn.birthday.defaults.day + "]");
            $day.empty();
            var selectedYear = $year.find("option:selected").val();
            var selectedMonth = $month.find("option:selected").val();
            if (selectedMonth == 2 && $.fn.birthday.IsRunYear(selectedYear)) {//如果是闰年
                var c = "<option value=''>--日--</option>";
                for (var i = 1; i <= 29; i++) {
                    c += "<option value='" + i + "'>" + i + "日</option>";
                }
                $day.append(c);
            } else {//如果不是闰年也没选2月份
                var c = "<option value=''>--日--</option>";
                for (var i = 1; i <= MonHead[selectedMonth - 1]; i++) {
                    c += "<option value='" + i + "'>" + i + "日</option>";
                }
                $day.append(c);
            }
            //重新更新一下chosen方法
            $day.trigger("chosen:updated");
        });
    };
    $.fn.birthday.IsRunYear = function (selectedYear) {
        return (0 == selectedYear % 4 && (selectedYear % 100 != 0 || selectedYear % 400 == 0));
    };
    $.fn.birthday.defaults = {
        year: "year",
        month: "month",
        day: "day"
    };
})(jQuery);
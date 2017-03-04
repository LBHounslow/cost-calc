var Spend = {

    ajaxResponse: [],
    spendByClients: {},
    spendByServices: {},
    clientSpend: {},

    secureUrl: baseUrl,

    getFilter: function () {

        /* get the filter values */
        dateRange = this.getDateRange();
        serviceTypes = this.getServiceTypes();
        tempFilter = this.getTempAccomFilter();
        troubledFilter = this.getTroubledFilter();
        hbSwitchFilter = this.getHbSwitchFilter();

        /* save them to object for easy retrieval */
        this.filter = {
            startDate: moment(dateRange.startDate).format('YYYY-MM-DD'),
            endDate: moment(dateRange.endDate).format('YYYY-MM-DD'),
            serviceTypes: encodeURI(serviceTypes),
            tempFilter: tempFilter,
            troubledFilter: troubledFilter,
            hbSwitchFilter: hbSwitchFilter,
        };
    },

    getSpendByClients: function (callback) {

        var params = [
            {tempFilter: this.filter.tempFilter},
            {troubledFilter: this.filter.troubledFilter},
            {hbSwitchFilter: this.filter.hbSwitchFilter},
            {start: this.filter.startDate},
            {end: this.filter.endDate},
            {serviceType: this.filter.serviceTypes},
        ];

        var baseUrl = "/reports/spend-by-client";
        var url = this.generateUrlWithGetParams(baseUrl, params);

        var that = this;

        $.ajax({
            type: "GET",
            url: url,
            dataType: "json",
            beforeSend: function () {
                $('#spend-by-client-table').bootstrapTable('removeAll');
            },
            success: function (response) {
                that.spendByClients = response;
                $('#spend-by-client-table').bootstrapTable('load', response);
                callback();

            },
        });
    },

    getSpendByServices: function (callback) {

        var params = [
            {tempFilter: this.filter.tempFilter},
            {troubledFilter: this.filter.troubledFilter},
            {hbSwitchFilter: this.filter.hbSwitchFilter},
            {start: this.filter.startDate},
            {end: this.filter.endDate},
            {serviceType: this.filter.serviceTypes},
        ];

        var baseUrl = "/reports/spend-by-service";
        var url = this.generateUrlWithGetParams(baseUrl, params);

        var that = this;

        $.ajax({
            type: "GET",
            url: url,
            dataType: "json",
            beforeSend: function () {
                $('#spend-by-service-table').bootstrapTable('removeAll');
            },
            success: function (response) {
                that.spendByServices = response;
                $('#spend-by-service-table').bootstrapTable('load', response);
                callback();
            },
        });
    },

    getClientSpend: function (clientId, callback) {

        var params = [
            {clientId: clientId},
            {tempFilter: this.filter.tempFilter},
            {troubledFilter: this.filter.troubledFilter},
            {hbSwitchFilter: this.filter.hbSwitchFilter},
            {start: this.filter.startDate},
            {end: this.filter.endDate},
            {serviceType: this.filter.serviceTypes},
        ];

        var baseUrl = "/reports/client-spend";
        var url = this.generateUrlWithGetParams(baseUrl, params);

        var that = this;

        $.ajax({
            type: "GET",
            url: url,
            dataType: "json",
            beforeSend: function () {
                $('#client-spend-table').bootstrapTable('removeAll');
            },
            success: function (response) {
                that.clientSpend = response;
                $('#client-spend-table').bootstrapTable('load', response);
                $("#client-spend-table tr td:nth-child(4), #client-spend-table tr td:nth-child(5)").each(function () {
                    if ($(this).text() == '-') {

                    } else {
                        var d = moment($(this).text()).format("DD/MM/YYYY");
                        $(this).html(d);
                    }
                });
                callback();

            },
        });
    },

    getDateRange: function () {
        startDate = $(".input-daterange").data("datepicker").pickers[0].getUTCDate();
        endDate = $(".input-daterange").data("datepicker").pickers[1].getUTCDate();

        return {
            "startDate": startDate,
            "endDate": endDate
        };
    },

    getServiceTypes: function () {
        return $('#service-type-select').val();
    },

    getTempAccomFilter: function () {
        return $('input[name=tempFilter]:checked').val();
    },

    getTroubledFilter: function () {
        return $('input[name=troubledFilter]:checked').val();
    },

    getHbSwitchFilter: function () {
        return $('input[name=hbSwitchFilter]:checked').val();
    },


    generateUrlWithGetParams: function (baseUrl, params) {

        var url = this.secureUrl + baseUrl;

        /* loop through array */
        for (var i = 0; i < params.length; i++) {
            /* loop through object */
            for (var property in params[i]) {
                /* check if first param */
                if (i === 0) {
                    var op = '?';
                } else {
                    var op = '&';
                }
                url += op + encodeURIComponent(property) + '=' + encodeURIComponent(params[i][property]);
            }
        }

        return url;
    },


};
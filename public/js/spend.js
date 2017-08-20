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
        serviceFilter = this.getServiceFilter();
        serviceNeeds = this.getServiceNeeds();
        needFilter = this.getNeedFilter();
        fileTypeFilter = this.getFileTypeFilter();
        ageRangeFilter = this.getAgeRangeFilter();

        /* save them to object for easy retrieval */
        this.filter = {
            startDate: moment(dateRange.startDate).format('YYYY-MM-DD'),
            endDate: moment(dateRange.endDate).format('YYYY-MM-DD'),
            serviceTypes: encodeURI(serviceTypes),
            serviceFilter: serviceFilter,
            serviceNeeds: encodeURI(serviceNeeds),
            needFilter: needFilter,
            fileTypeFilter: fileTypeFilter,
            ageRangeFilter: ageRangeFilter,
        };
    },

    getSpendByClients: function (callback) {

        var params = [
            {serviceFilter: this.filter.serviceFilter},
            {needFilter: this.filter.needFilter},
            {start: this.filter.startDate},
            {end: this.filter.endDate},
            {serviceType: this.filter.serviceTypes},
            {serviceNeed: this.filter.serviceNeeds},
            {fileTypeFilter: JSON.stringify(this.filter.fileTypeFilter)},
            {ageRangeFilter: JSON.stringify(this.filter.ageRangeFilter)},
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
            {serviceFilter: this.filter.serviceFilter},
            {needFilter: this.filter.needFilter},
            {start: this.filter.startDate},
            {end: this.filter.endDate},
            {serviceType: this.filter.serviceTypes},
            {serviceNeed: this.filter.serviceNeeds},
            {fileTypeFilter: JSON.stringify(this.filter.fileTypeFilter)},
            {ageRangeFilter: JSON.stringify(this.filter.ageRangeFilter)},
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
            {serviceFilter: this.filter.serviceFilter},
            {needFilter: this.filter.needFilter},
            {start: this.filter.startDate},
            {end: this.filter.endDate},
            {serviceType: this.filter.serviceTypes},
            {serviceNeed: this.filter.serviceNeeds},
            {fileTypeFilter: JSON.stringify(this.filter.fileTypeFilter)},
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
                $("#client-spend-table tr td:nth-child(5), #client-spend-table tr td:nth-child(6)").each(function () {
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

    getServiceNeeds: function () {
        return $('#service-need-select').val();
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

    getServiceFilter: function () {
        return $('input[name=serviceFilter]:checked').val();
    },

    getNeedFilter: function () {
        return $('input[name=needFilter]:checked').val();
    },

    getFileTypeFilter: function () {
        return $('input.fileTypeFilter:checked').map(function () {
            return {
                key: this.name,
                val: $(this).val()
            }
        }).get();
    },

    getAgeRangeFilter: function () {
        return slider.noUiSlider.get();
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
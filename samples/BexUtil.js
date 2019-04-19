(function (root) {
    var BexJS = undefined;
    var BexUtil = {};

    BexUtil.createTicket = function (url, params) {
        return $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: JSON.stringify(params)
        }).then(function (response) {
            if (!BexJS) {
                return $
                    .getScript(response.config.baseJs)
                    .then(function () {
                        BexJS = Bex;
                        return response.response;
                    });
            }
            return response.response;
        })
    };

    BexUtil.queryTicket = function (url, params) {
        return $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: JSON.stringify(params)
        }).then(function (response) {
            /**
             * {"amount":"1000","error":false,"status":"SUCCESS","message":"\u00d6deme tamamland\u0131","detail":null}
             */
            if (response.status !== "SUCCESS") {
                throw new Error("İşlem hatası !")
            }
            return response
        });
    };
    BexUtil.showModal = function (url, params, modalOptions) {
        BexUtil
            .createTicket(url, params)
            .then(function (ticket) {
                Bex.init(ticket, "modal", modalOptions);
            })
            .catch(function (err) {
                console.log('Error', err);
            })
    };
    root.BexUtil = BexUtil;
})(this);
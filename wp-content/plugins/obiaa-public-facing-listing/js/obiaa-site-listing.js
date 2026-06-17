jQuery(document).ready(
    function ($) {
        var db = {

            loadData: function (filter) {
                return $.grep(
                    db.contacts, function (contact) {
                        return (!filter.businessType || contact.businessType.indexOf(filter.businessType) > -1)
                        && (!filter.category || contact.category.indexOf(filter.category) > -1)
                        && (!filter.subCategory || contact.subCategory.indexOf(filter.subCategory) > -1)
                    }
                );
            }

        };
        window.db = db;

        // Data
        db.contacts = contacts;

        // Filters
        var filters = { 'businessType': [], 'category': [], 'subCategory': [] };
        var keys = Object.keys(filters);
        keys.forEach(
            (e) => {
            filters[e] = db.contacts.map(x => x[e]);    //Get column
            filters[e] = $.grep([...new Set(filters[e])], n => n);   //Remove empty, duplicates
            db[e] = [];
            filters[e].forEach(
                    (v) => {
                    db[e].push({ 'name': v });
                    }
                );
            db[e].unshift("");
            }
        );

        $("#jsGrid").jsGrid(
            {
                width: "100%",
                height: "650",

                inserting: false,
                editing: false,
                sorting: true,
                paging: true,
                filtering: true,

                pageIndex: 1,
                pageSize: 50,
                pageButtonCount: 5,
                pagerFormat: "Pages: {first} {prev} {pages} {next} {last}    {pageIndex} of {pageCount}",

                data: db.contacts,
                controller: db,

                noDataContent: "No data! Please check Dashboard -> OBIAA Site Listing Options -> API key and URL.",

                fields: [
                {
                    name: "image_URL", title: "Logo", width: 80, filtering: false, sorting: false, itemTemplate: function (value) {
                        if (value != "") {
                            return `<img src="${value}" width="42" height="42">`;
                        }
                        return '';
                    }
                },
                { name: "organization_name", title: 'Business Name', filtering: false, type: "text", width: 100 },
                { name: "address", title: 'Address', sorting: false, filtering: false, type: "text", width: 250 },
                { name: "businessType", title: 'Business Type', type: "select", width: 200, items: db.businessType, valueField: "name", textField: "name" },
                { name: "category", title: 'Category', type: "select", width: 200, items: db.category, valueField: "name", textField: "name" },
                { name: "subCategory", title: 'Sub Category', type: "select", width: 200, items: db.subCategory, valueField: "name", textField: "name" },
                ],
            }
        );
        $("#sort").click(
            function () {
                var field = $("#sortingField").val();
                $("#jsGrid").jsGrid("sort", field);
            }
        );
        $("#jsGrid").jsGrid("sort", "organization_name");
    }
);
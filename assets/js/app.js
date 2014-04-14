var App = (function()
{
    /**
     * Application Options
     * @type {{}}
     */
    var app_options = {
        ajax_url: 'index.php?view=json_members',
        user_id:0
    };

    /**
     * Set Application Options
     *
     * @param options
     * @private
     */
    function _setOptions(options)
    {
        if (typeof options !== "undefined")
        {
            app_options = $.merge(options, app_options);
        }
    }

    /**
     * Set User Id
     *
     * @param id
     * @private
     */
    function _setUserId(id){ app_options.user_id = id; }

    /**
     * Get User Id
     *
     * @returns {*}
     * @private
     */
    function _getUserId(){ return app_options.user_id; }

    /**
     * Enable Preloader
     *
     * @private
     */
    function _enablePreloader()
    {
        $('#team_member_viewport').button('loading');
    }

    /**
     * Disable Preloader
     *
     * @private
     */
    function _disablePreloader()
    {
        $('#team_member_viewport').button('reset');
    }

    /**
     * Set Active Team Member
     *
     * @param element
     * @private
     */
    function _setActive(element)
    {
        //-- first search for active elements and remove class
        $('.active').removeClass('active');

        //-- set active class
        element.parent().addClass('active');
    }

    function _getDevices()
    {
        $.ajax({
            url: app_options.ajax_url,
            type: 'POST',
            DataType: 'JSON',
            cache: false,
            data:
            {
                user_id: _getUserId()
            },

            beforeSend:function()
            {
                //-- set preloader
                _enablePreloader();

                //-- clear devices table
                $('#devices tbody').html('');

            },
            success: function(result)
            {
                //-- disable preloader
                _disablePreloader();

                if (result.status)
                {
                    //-- hide default content
                    $('#welcome_content').hide();

                    //-- set title
                    $('#page_header').html('Devices owned by '+ result.user.first_name + ' ' + result.user.last_name);

                    $(result.data).each(function(index, elements)
                    {
                        if (elements.devices_count > 0)
                        {
                            $(elements.devices).each(function(i, element)
                            {
                                var tr = $('<tr />'),
                                    id = $('<td />').html(element.id),
                                    brand = $('<td />').html(element.brand),
                                    model = $('<td />').html(element.model),
                                    custody_from = $('<td />').html(element.custody_from),
                                    custody_till = $('<td />').html(element.custody_till);

                                tr.append(id).append(brand)
                                    .append(model).append(custody_from)
                                    .append(custody_till);

                                $('#devices tbody').append(tr);
                            });

                            $('#page_header').show();
                            $('#devices').show();
                        }
                        else
                        {
                            $('#devices').hide();
                            $('#page_header').html(result.user.first_name + ' ' + result.user.last_name + ' does not have any devices!');
                        }
                    });

                    //-- show data
                    $('#team_member_data').show();

                    //-- enable reload
                    _enableReload();
                }
                else
                {
                    //-- we have an error?
                    console.error('Hoops, we have an error!!!');
                }
            },
            error: function(data, status)
            {
                alert('Sorry :( something bad happened!');
            }
        });
    }

    function _enableReload()
    {
        $('#reload_btn').show().click(function(event)
        {
            //-- get team member devices
            _getDevices();
        });
    }

    /**
     * Run the Application
     *
     * @private
     */
    function _run()
    {
        $('.team_member').click(function(event)
        {
            event.preventDefault();

            //-- get user id
            var user_id = parseInt($(this).attr('user-id'));

            //-- set user ID
            _setUserId(user_id);

            //-- set active
            _setActive($(this));

            //-- get team member devices
            _getDevices();
        });
    }

    /**
     * Public functions Object
     *
     * @type {Object}
     */
    return {
        /**
         * Run the App
         *
         * @param options
         * @returns {*}
         */
        run:function(options)
        {
            //-- set app options
            _setOptions(options);

            //-- run the application
            return _run();
        }
    };
})();
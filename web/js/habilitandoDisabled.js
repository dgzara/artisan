    $(document).ready(function()
    {
        $('#form').submit(function(event)
        {
            var $inputs = $('#form :input');
            
            $inputs.each(function()
            {
                $(this).removeAttr('disabled');
            })

        });
    });

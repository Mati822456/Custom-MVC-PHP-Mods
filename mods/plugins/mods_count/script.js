$(document).ready(function () {
    if(window.location.pathname == '/'){
        let plugins_card = $('.plugins-card');
        let themes_card = $('.themes-card');

        $.ajax({
            url: '/mods-list',
            type: 'GET',
            success: function(data){
                let mods = JSON.parse(data);
                let prefix_plugins = mods.plugins > 1 ? 'plugins' : 'plugin';
                let prefix_themes = mods.themes > 1 ? 'themes' : 'theme';
                
                plugins_card.before('<p style="font-weight:600;margin-top:5px;"><span style="color:#f53b57;">' + mods.plugins + '</span> ' + prefix_plugins + ' activated</p>');
        
                themes_card.before('<p style="font-weight:600;"><span style="color:#f53b57;">' + mods.themes + '</span> ' + prefix_themes + ' activated</p>');
            }
        })

    }
});
$(document).ready(function () {
    if(window.location.pathname == '/'){
        let index_h2 = $('.index h2');

        $.ajax({
            url: '/get-weather',
            type: 'GET',
            success: function(data){
                let weather = JSON.parse(data);
                
                let weather_code = weather.current_weather.weathercode;
                let weather_icon = $('<img>', {
                    'css': {
                        'width': '48px',
                        'height': '48px'
                    }
                });

                let location_name = $('<h4>').append('Warsaw');

                let temp_wind_container = $('<div>', {
                    'css': {
                        'text-align': 'center'
                    }
                });
                
                let temp = weather.current_weather.temperature;
                let temp_div = $('<div>', {
                    'css': {
                        'font-size': '14px',
                    }
                }).append(temp + '°C');
                
                let wind_speed = weather.current_weather.windspeed;
                let wind_div = $('<div>', {
                    'css': {
                        'font-size': '14px',
                    }
                }).append(wind_speed + ' km/h');

                temp_wind_container.append(temp_div);
                temp_wind_container.append(wind_div);

                let is_day = weather.current_weather.is_day;

                if(is_day == 1){
                    if(weather_code == 0){
                        weather_icon.attr('src', './public/mods/plugins/weather_viewer/icons/clear.svg');
                    }
                    if(weather_code == 1){
                        weather_icon.attr('src', './public/mods/plugins/weather_viewer/icons/mainly_clear.svg');
                    }
                    if(weather_code == 2){
                        weather_icon.attr('src', './public/mods/plugins/weather_viewer/icons/partly_cloudy.svg');
                    }
                    if(weather_code == 3 || weather_code == 45 || weather_code == 48 || weather_code == 51 || weather_code == 53 || weather_code == 55 || weather_code == 56 || weather_code == 57){
                        weather_icon.attr('src', './public/mods/plugins/weather_viewer/icons/overcast.svg');
                    }
                    if(weather_code == 61 || weather_code == 63 || weather_code == 65 || weather_code == 66 || weather_code == 67 || weather_code == 80 || weather_code == 81 || weather_code == 82){
                        weather_icon.attr('src', './public/mods/plugins/weather_viewer/icons/rain.svg');
                    }
                    if(weather_code == 71 || weather_code == 73 || weather_code == 75 || weather_code == 77 || weather_code == 85 || weather_code == 86){
                        weather_icon.attr('src', './public/mods/plugins/weather_viewer/icons/snow.svg');
                    }
                    if(weather_code == 95 || weather_code == 96 || weather_code == 99){
                        weather_icon.attr('src', './public/mods/plugins/weather_viewer/icons/thunderstorm.svg');
                    }
                }else{
                    weather_icon.attr('src', './public/mods/plugins/weather_viewer/icons/moon.svg');
                }

                let newCard = $('<div>', {
                    'class': 'card-redirect', 
                    'css': {
                        'margin': '0 0 5px 0',
                        'padding': '10px 20px',
                    }
                });
                
                newCard.append(location_name);

                newCard.append(temp_wind_container);

                newCard.append(weather_icon);

                index_h2.after(newCard);
            }
        })

    }
});
$(document).ready(function () {
    let topnav = $('.topnav');
    let newNav;

    if(window.location.pathname == '/database'){
        newNav = $('<a class="active" href="/database">Database</a>');
    }else{
        newNav = $('<a href="/database">Database</a>');
    }

    if(window.location.pathname == '/'){
        let indexSection = $('.index');
        let newCardRedirect = $(
            '<div class="card-redirect"><img src="./mods/plugins/database_viewer/image.svg" style="width:32px;height:32px;"><h3>Database</h3><a href="/database"><i class="fa-solid fa-right-long fa-xl"></i></a></div>');
        indexSection.append(newCardRedirect);
    }

    topnav.append(newNav);
});

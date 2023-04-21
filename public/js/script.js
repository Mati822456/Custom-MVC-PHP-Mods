$(document).ready(function () {
    window.showModal = function(modalType, text, name, type){
        let $modal = $('<div>', {"class": "modal"});
        let $modal_content = $('<div>', {"class": "modal_content"});

        let img = $('<img>');

        switch(modalType){
            case '1':
                img.attr('src', '../images/question_sign.svg');
                break;
            case '2':
                img.attr('src', '../images/info_sign.svg');
                break;
            case '3':
                img.attr('src', '../images/warning_sign.svg');
                break;
            default:
                img.attr('src', '../images/info_sign.svg');
        }

        let $head = $('<div>', {"class": "head"}).append(img);
        $head.append('Are you sure?<a onClick="closeModal()">&times;</a>');

        let $body = $('<div>', {"class": "body"}).append(text + '</br><span class="name">' + name + '</span>');

        let $actions = $('<div>', {"class": "actions"}).append('<a href="/uninstall?name=' + name + '&type=' + type + '">Yes, uninstall</a><a onClick="closeModal()">Cancel</a>');

        $modal_content.append($head);
        $modal_content.append($body);
        $modal_content.append($actions);

        $modal.append($modal_content);
        
        $('body').append($modal);
        $('body').css('overflow', 'hidden');

        $modal.on('click', function(e){
            if ($(e.target).parents(".modal_content").length === 0 && e.target.className != 'modal_content') {
                closeModal();
            }
        });
    }
    window.closeModal = function(){
        if($('div.modal').length){
            $('div.modal').remove();
            $('body').css('overflow', '');
        }
    }
});
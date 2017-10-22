/**
 * showBSModal({
 *   remote: 'path/to/popup/html/file'
 * });
 *
 * showBSModal({
 *   title: "Event Listener",
 *   body: "You can perform certain actions onShow or onHide as well.",
 *   onShow: function(e){
 *       //do something
 *   },
 *   onHide: function(e){
 *       //do something
 *   }
 * });
 *
 * showBSModal({
 *   title: "Do you want to logo out?",
 *   body: "You will loose all unsaved work, press 'Cancel' to return to page or 'Confirm' to log out",
 *   size: "small",
 *   actions: [{
 *       label: 'Cancel',
 *       cssClass: 'btn-success',
 *       onClick: function(e){
 *           $(e.target).parents('.modal').modal('hide');
 *       }
 *   },{
 *       label: 'Confirm',
 *       cssClass: 'btn-danger',
 *       onClick: function(e){
 *           //proceed to log out
 *       }
 *   }]
 * });
 */
window.showBSModal = function self(options) {

    var options = $.extend({
            title : '',
            body : '',
            remote : false,
            backdrop : 'static',
            modalClass: '',
            size : false,
            onShow : false,
            onHide : false,
            actions : false
        }, options);

    self.onShow = typeof options.onShow == 'function' ? options.onShow : function () {};
    self.onHide = typeof options.onHide == 'function' ? options.onHide : function () {};

    if (self.$modal == undefined) {
        self.$modal = $('<div class="modal fade ' + options.modalClass + '"><div class="modal-dialog"><div class="modal-content"></div></div></div>').appendTo('body');
        self.$modal.on('shown.bs.modal', function (e) {
            self.onShow.call(this, e);
        });
        self.$modal.on('hidden.bs.modal', function (e) {
            self.onHide.call(this, e);
        });
    }

    var modalClass = {
        small : "modal-sm",
        large : "modal-lg"
    };

    self.$modal.data('bs.modal', false);
    self.$modal.find('.modal-dialog').removeClass().addClass('modal-dialog ' + (modalClass[options.size] || ''));
    self.$modal.find('.modal-content').html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">${title}</h4></div><div class="modal-body">${body}</div><div class="modal-footer"></div>'.replace('${title}', options.title).replace('${body}', options.body));

    var footer = self.$modal.find('.modal-footer');
    if (Object.prototype.toString.call(options.actions) == "[object Array]") {
        for (var i = 0, l = options.actions.length; i < l; i++) {
            options.actions[i].onClick = typeof options.actions[i].onClick == 'function' ? options.actions[i].onClick : function () {};
            $('<button type="button" class="btn ' + (options.actions[i].cssClass || '') + '">' + (options.actions[i].label || '{Label Missing!}') + '</button>').appendTo(footer).on('click', options.actions[i].onClick);
        }
    } else {
        $('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>').appendTo(footer);
    }

    self.$modal.modal(options);
};

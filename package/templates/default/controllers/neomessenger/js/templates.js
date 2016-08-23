(function() {
window["icms"] = window["icms"] || {};
window["icms"]["neomessenger"] = window["icms"]["neomessenger"] || {};
window["icms"]["neomessenger"]["templates"] = window["icms"]["neomessenger"]["templates"] || {};

window["icms"]["neomessenger"]["templates"]["contact"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {
__p += '<li id="nm-contact' +
((__t = ( user.id )) == null ? '' : __t) +
'" class="user_contact" rel="' +
((__t = ( user.id )) == null ? '' : __t) +
'">\r\n\r\n    ';
 if (user.new_messages > 0) { ;
__p += '\r\n        <span class="counter" rel="' +
((__t = ( user.new_messages )) == null ? '' : __t) +
'">' +
((__t = ( user.new_messages )) == null ? '' : __t) +
'</span>\r\n    ';
 } ;
__p += '\r\n\r\n    ';
 if (user.is_ignored) { ;
__p += '\r\n        <div class="nm-contact-ignored"></div>\r\n    ';
 } ;
__p += '\r\n\r\n    <div class="nm-contact-image-wrap">\r\n\r\n        ';
 if (user.is_online) { ;
__p += '\r\n            <div class="nm-online-status"></div>\r\n        ';
 } ;
__p += '\r\n\r\n        <div class="nm-contact-image">\r\n            <img src="' +
((__t = ( user.avatar )) == null ? '' : __t) +
'">\r\n        </div>\r\n\r\n    </div>\r\n\r\n    <div class="username">' +
((__t = ( user.nickname )) == null ? '' : __t) +
'</div>\r\n\r\n</li>';

}
return __p
}})();
(function() {
window["icms"] = window["icms"] || {};
window["icms"]["neomessenger"] = window["icms"]["neomessenger"] || {};
window["icms"]["neomessenger"]["templates"] = window["icms"]["neomessenger"]["templates"] || {};

window["icms"]["neomessenger"]["templates"]["contactPanel"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {
__p += '<div class="nm-contactpanel-contact">\r\n    <a href="' +
((__t = ( contact.url )) == null ? '' : __t) +
'">\r\n        <div class="nm-contact-image-wrap">\r\n\r\n            ';
 if (contact.is_online) { ;
__p += '\r\n                <div class="nm-online-status"></div>\r\n            ';
 } ;
__p += '\r\n\r\n            <div class="nm-contact-image">\r\n                <img src="' +
((__t = ( contact.avatar )) == null ? '' : __t) +
'">\r\n            </div>\r\n\r\n            <div class="nm-contact-nickname">' +
((__t = ( contact.nickname )) == null ? '' : __t) +
'</div>\r\n\r\n        </div>\r\n    </a>\r\n</div>\r\n\r\n<div class="nm-cp-actions">\r\n    <input type="button" class="nm-button nm-button-small" value="';
 if (contact.is_ignored) { ;
__p +=
((__t = ( LANG_NEOMESSENGER_CONTACT_FORGIVE )) == null ? '' : __t);
 } else { ;
__p +=
((__t = ( LANG_NEOMESSENGER_CONTACT_IGNORE )) == null ? '' : __t);
 } ;
__p += '" onclick="icms.neomessenger.contacts.';
 if (contact.is_ignored) { ;
__p += 'forgive';
 } else { ;
__p += 'ignore';
 } ;
__p += '(' +
((__t = ( contact.id )) == null ? '' : __t) +
')">\r\n    <input type="button" class="nm-button nm-button-small" value="' +
((__t = ( LANG_DELETE )) == null ? '' : __t) +
'" onclick="icms.neomessenger.contacts.remove(' +
((__t = ( contact.id )) == null ? '' : __t) +
')">\r\n</div>\r\n\r\n<div class="clearfix"></div>';

}
return __p
}})();
(function() {
window["icms"] = window["icms"] || {};
window["icms"]["neomessenger"] = window["icms"]["neomessenger"] || {};
window["icms"]["neomessenger"]["templates"] = window["icms"]["neomessenger"]["templates"] || {};

window["icms"]["neomessenger"]["templates"]["mainModal"] = function(obj) {
obj || (obj = {});
var __t, __p = '';
with (obj) {
__p += '<div id="nm-overlay">\r\n    <div id="nm-dialog">\r\n\r\n        <div class="nm-header">\r\n            <div class="nm-toggle nm-icon-hamburger-wrap">\r\n                <span class="nm-icon-bar"></span>\r\n                <span class="nm-icon-bar"></span>\r\n                <span class="nm-icon-bar"></span>\r\n            </div>\r\n            <div class="nm-title">' +
((__t = ( LANG_NEOMESSENGER_MAIN_TITLE )) == null ? '' : __t) +
'</div>\r\n            <div class="nm-close"></div>\r\n        </div>\r\n\r\n        <div class="nm-body">\r\n            <div class="nm-loading"></div>\r\n            <div class="nm-nomess">' +
((__t = ( LANG_NEOMESSENGER_NO_MESS )) == null ? '' : __t) +
'</div>\r\n            <div class="nm-content">\r\n\r\n                <div class="nm-left">\r\n\r\n                    <div class="nm-search-c-wrap">\r\n                        <div class="nm-inp-c-bl">\r\n                            <div class="nm-search-icon"></div>\r\n                            <input type="text" id="nm-search-inp" value="" placeholder="' +
((__t = ( LANG_NEOMESSENGER_SEARCH_PLACEHOLDER )) == null ? '' : __t) +
'">\r\n                            <div id="nm-search-clr"></div>\r\n                        </div>\r\n                    </div>\r\n\r\n                    <ul id="nm-userlist"></ul>\r\n\r\n                </div>\r\n\r\n                <div class="nm-right">\r\n                    <div id="nm-msg-loading"></div>\r\n                    <div id="nm-chat-wrapper">\r\n                        <div id="nm-contact-panel"></div>\r\n                        <div id="nm-chat" class="conversation-inner"></div>\r\n                        <div id="nm-composer">\r\n                            ' +
((__t = ( htmlEditor )) == null ? '' : __t) +
'\r\n                        </div>\r\n                    </div>\r\n                </div>\r\n\r\n                <div class="clearfix"></div>\r\n\r\n            </div>\r\n\r\n        </div>\r\n\r\n    </div>\r\n</div>';

}
return __p
}})();
(function() {
window["icms"] = window["icms"] || {};
window["icms"]["neomessenger"] = window["icms"]["neomessenger"] || {};
window["icms"]["neomessenger"]["templates"] = window["icms"]["neomessenger"]["templates"] || {};

window["icms"]["neomessenger"]["templates"]["message"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {



    var className = 'conversation-item clearfix';

    if (is_my) {
        className += ' item-right';
    } else {
        className += ' item-left';
    }

    if (is_new) {
        className += ' new'
    }

    var temp = temp || false;

;
__p += '\r\n\r\n<div id="nm-message-' +
((__t = ( msg.id )) == null ? '' : __t) +
'" class="' +
((__t = ( className )) == null ? '' : __t) +
'" rel="' +
((__t = ( msg.id )) == null ? '' : __t) +
'">\r\n\r\n    <div class="conversation-user">\r\n        <img src="' +
((__t = ( msg.user.avatar )) == null ? '' : __t) +
'" class="nm-message-user-photo">\r\n    </div>\r\n\r\n    <div class="conversation-body">\r\n\r\n        ';
 if (!temp) { ;
__p += '\r\n            <div class="author">' +
((__t = ( msg.user.nickname )) == null ? '' : __t) +
'</div>\r\n        ';
 } ;
__p += '\r\n\r\n        <div class="msg-content">\r\n            ';
 if (!temp) { ;
__p += '\r\n                <div class="nm-message-time"><span>' +
((__t = ( msg.time )) == null ? '' : __t) +
'</span></div>\r\n            ';
 } ;
__p += '\r\n            <span class="nm-message-text">' +
((__t = ( msg.content )) == null ? '' : __t) +
'</span>\r\n        </div>\r\n\r\n    </div>\r\n\r\n</div>';

}
return __p
}})();
(function() {
window["icms"] = window["icms"] || {};
window["icms"]["neomessenger"] = window["icms"]["neomessenger"] || {};
window["icms"]["neomessenger"]["templates"] = window["icms"]["neomessenger"]["templates"] || {};

window["icms"]["neomessenger"]["templates"]["noticesButton"] = function(obj) {
obj || (obj = {});
var __t, __p = '';
with (obj) {
__p += '<li class="info notices-counter">\r\n    <a class="item" href="/messages/notices">\r\n        <span class="wrap">Уведомления</span>\r\n    </a>\r\n</li>';

}
return __p
}})();
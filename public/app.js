/**
 * @access public
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
(function ($) {

    /**
     *
     * @returns {string}
     */
    var broofa = function () {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    };

    /**
     *
     * @param value
     * @returns {boolean}
     */
    var isEmpty = function (value) {
        return value === undefined || value === null || value === "";
    };

    /**
     *
     * @param options
     * @param context
     * @constructor
     */
    var TODOSocket = function (options, context) {
        this.context = context;
        this.$options = $.extend({}, this.DEFAULTS, options);
        this.connection = new WebSocket(this.$options.url);
    };

    /**
     *
     * @type {{DEFAULTS: {url: string}, on: on, send: send}}
     */
    TODOSocket.prototype = {
        DEFAULTS: {
            url: 'ws://localhost:9001'
        },

        /**
         *
         * @param name
         * @param listener
         */
        on: function (name, listener) {
            this.connection.addEventListener(name, listener);
        },

        /**
         *
         * @param msg
         */
        send: function (msg) {
            this.connection.send(JSON.stringify(msg));
        }
    };

    /**
     *
     * @param options
     * @constructor
     */
    function TODOField(options) {
        this.$options = $.extend({}, this.DEFAULTS, options);
        if ('defaultValue' in this.$options) {
            this.value = this.$options['defaultValue'];
        }
    }

    /**
     *
     * @type {{isField: boolean, isString: boolean, value: undefined, DEFAULTS: {}}}
     */
    TODOField.prototype = {
        isField: true,
        isString: true,
        value: undefined,
        DEFAULTS: {}
    };

    /**
     *
     * @returns {boolean}
     */
    TODOField.prototype.isEmpty = function () {
        return isEmpty(this.value);
    };

    /**
     *
     * @param value
     * @returns {TODOField}
     */
    TODOField.prototype.setValue = function (value) {
        this.value = value;
        return this;
    };

    /**
     *
     * @returns {*}
     */
    TODOField.prototype.getValue = function () {
        return !this.isEmpty(this.value) ? this.value : '';
    };

    /**
     *
     * @returns {*}
     */
    TODOField.prototype.serialize = function () {
        return this.getValue();
    };

    /**
     *
     * @param options
     * @returns {*}
     */
    TODOField.factory = function (options) {
        switch (options.type) {
            case 'identifier':
                return new TODOFieldIdentifier(options);
            case 'integer':
                return new TODOFieldInt(options);
            case 'date':
                return new TODOFieldDate(options);
            default:
                return new TODOField(options);
        }
    };

    /**
     *
     * @param options
     * @constructor
     */
    function TODOFieldIdentifier(options) {
        this.isString = false;
        this.isIdentifier = true;
        this.$options = $.extend({}, this.DEFAULTS, options);
        if ('defaultValue' in this.$options) {
            this.value = this.$options['defaultValue'];
        }
    }

    // extends
    TODOFieldIdentifier.prototype = Object.create(TODOField.prototype);
    TODOFieldIdentifier.prototype.constructor = TODOFieldIdentifier;

    TODOFieldIdentifier.prototype.generate = function () {
        this.setValue(broofa());
    };

    /**
     *
     * @param options
     * @constructor
     */
    function TODOFieldInt(options) {
        this.isString = false;
        this.isInteger = true;
        this.$options = $.extend({}, this.DEFAULTS, options);
        if ('defaultValue' in this.$options) {
            this.value = this.$options['defaultValue'];
        }
    }

    // extends
    TODOFieldInt.prototype = Object.create(TODOField.prototype);
    TODOFieldInt.prototype.constructor = TODOFieldInt;

    /**
     *
     * @param value
     * @returns {TODOFieldInt}
     */
    TODOFieldInt.prototype.setValue = function (value) {
        this.value = parseInt(value);
        return this;
    };

    /**
     *
     * @param options
     * @constructor
     */
    function TODOFieldDate(options) {
        this.isString = false;
        this.isDate = true;
        this.$options = $.extend({}, this.DEFAULTS, options);
        if ('defaultValue' in this.$options) {
            this.value = this.$options['defaultValue'];
        }
    }

    // extends
    TODOFieldDate.prototype = Object.create(TODOField.prototype);
    TODOFieldDate.prototype.constructor = TODOFieldDate;

    /**
     *
     * @param value
     * @returns {TODOFieldDate}
     */
    TODOFieldDate.prototype.setValue = function (value) {
        if (!isEmpty(value) && !TODOFieldDate.isDate(value)) {
            value = Date.parse(value);
            if (0 < value) {
                value = new Date(value);
            }
        }
        this.value = value;
        return this;
    };

    /**
     *
     * @returns {string}
     */
    TODOFieldDate.prototype.serialize = function () {
        if (!isEmpty(this.getValue()) && TODOFieldDate.isDate(this.getValue())) {
            var value = this.getValue();
            return [
                [value.getFullYear(), value.getMonth() + 1, value.getDate()].join('-'),
                value.toLocaleTimeString()
            ].join(' ');
        }

        return '';
    };

    /**
     *
     * @param value
     * @returns {boolean}
     */
    TODOFieldDate.isDate = function (value) {
        return Object.prototype.toString.call(value) === '[object Date]';
    };

    /**
     *
     * @param value
     * @returns {number | Date}
     */
    TODOFieldDate.parse = function (value) {
        value = Date.parse(value);

        if (!isEmpty(value) && !TODOFieldDate.isDate(value)) {
            value = new Date(value);
        }

        return value;
    };

    /**
     *
     * @param data
     * @param context
     * @constructor
     */
    var TODORecord = function (data, context) {
        this.context = context;
        this.origData = data;

        // init fields
        $.each(this.fields, $.proxy(function (i, field) {
            this.$data = $.extend(
                this.$data, {[field.name]: TODOField.factory(field)}
            );
        }, this));

        this.setData(data);

        var identifier = this.$data[this.idProperty];
        if (identifier.isEmpty()) {
            identifier.generate();
        }

        this.$container = $(this.tmp);
        this.$container.attr(this.idProperty, identifier.getValue());

        this.$checkInput = $('.form-check-input', this.$container);
        this.$editButton = $('.todo-edit', this.$container);
        this.$dropButton = $('.todo-drop', this.$container);
        this.$blockInput = $('div.form-control', this.$container);
        this.$editInput = $('input.form-control', this.$container);

        this.refresh();
        this.init();
    };

    TODORecord.prototype = {

        idProperty: 'id',

        fields: [{
            type: 'identifier',
            name: 'id'
        }, {
            type: 'integer',
            name: 'check',
            defaultValue: 0
        }, {
            type: 'string',
            name: 'description'
        }, {
            type: 'string',
            name: 'lockBy'
        }, {
            type: 'date',
            name: 'lockAt',
            // defaultValue: new Date(),
        }],

        tmp: '<div class="list-group-item">\n' +
            '    <div class="input-group todo-view">\n' +
            '\n' +
            '        <div class="form-check form-check-inline">\n' +
            '            <input class="form-check-input" type="checkbox" value="" />\n' +
            '        </div>\n' +
            '\n' +
            '        <div class="form-control mr-sm-2 border-0">\n' +
            '            <div class="todo-description"></div>\n' +
            '            <div class="d-none">\n' +
            '                <del class="todo-description"></del>\n' +
            '            </div>\n' +
            '        </div>\n' +
            '\n' +
            '        <span class="input-group-btn">\n' +
            '            <button class="btn btn-outline-info border-0 todo-edit" type="button">\n' +
            '                <i class="fas fa-pencil-alt"></i>\n' +
            '            </button>\n' +
            '            <button class="btn btn-outline-danger border-0 todo-drop" type="button">\n' +
            '                <i class="fas fa-trash"></i>\n' +
            '            </button>\n' +
            '        </span>\n' +
            '\n' +
            '    </div>\n' +
            '    <div class="input-group todo-change d-none">\n' +
            '        <input class="form-control mr-sm-2" type="text" />\n' +
            '        <span class="input-group-btn">\n' +
            '            <button class="btn btn-outline-success border-0 todo-commit" type="button">\n' +
            '                <i class="fas fa-check"></i>\n' +
            '            </button>\n' +
            '            <button class="btn btn-outline-warning border-0 todo-reject" type="button">\n' +
            '                <i class="fas fa-times"></i>\n' +
            '            </button>\n' +
            '        </span>\n' +
            '    </div>\n' +
            '</div>',

        init: function () {

            this.$checkInput.on('change', $.proxy(this.onCheck, this));
            this.$editButton.on('click', $.proxy(this.onEdit, this));
            this.$dropButton.on('click', $.proxy(this.onDrop, this));
            this.$editInput.on('keyup', $.proxy(this.onKeyUp, this));

            $('div.form-control', this.$container)
                .on('dblclick', $.proxy(this.onDblClick, this));
            $('.todo-commit', this.$container)
                .on('click', $.proxy(this.onCommit, this));
            $('.todo-reject', this.$container)
                .on('click', $.proxy(this.onReject, this));
        },

        /**
         *
         * @param key
         * @returns {*}
         */
        get: function (key) {
            if (key in this.$data) {
                return this.$data[key].getValue();
            }
        },

        /**
         *
         * @param key
         * @param value
         * @returns {TODORecord}
         */
        set: function (key, value) {
            if (key in this.$data) {
                this.$data[key].setValue(value);
            }
            return this;
        },

        /**
         *
         * @param flag
         */
        applyCheckedData: function (flag) {
            this.set('check', flag);
            this.$checkInput.prop('checked', flag).val(flag);

            if (flag) {
                $('div.todo-description', this.$container).addClass('d-none');
                $('div:has(> del.todo-description)', this.$container).removeClass('d-none');
                this.$editButton.addClass('disabled');
                this.$dropButton.addClass('disabled');
            } else {
                $('div.todo-description', this.$container).removeClass('d-none');
                $('div:has(> del.todo-description)', this.$container).addClass('d-none');
                this.$editButton.removeClass('disabled');
                this.$dropButton.removeClass('disabled');
            }
        },

        /**
         *
         * @returns {TODORecord}
         */
        refresh: function () {
            this.applyCheckedData(this.get('check'));
            $('.todo-description', this.$container).text(this.get('description'));
            this.$editInput.val(this.get('description'));

            if (this.isLocked()) {
                this.locked();
            }

            return this;
        },

        /**
         *
         * @param data
         * @returns {TODORecord}
         */
        setData: function (data) {
            $.each(this.fields, $.proxy(function (i, field) {
                if (field.name in data) {
                    this.set(field.name, data[field.name])
                }
            }, this));
            return this;
        },

        /**
         *
         */
        serialize: function () {
            var result = {};

            $.each(this.fields, $.proxy(function (i, field) {
                result[field.name] = this.$data[field.name].serialize();
            }, this));

            return result;
        },

        /**
         *
         * @param flag
         * @returns {TODORecord}
         */
        toggleLocked: function (flag) {
            this.$checkInput.prop('disabled', flag);
            /**
             *
             * @type {string}
             */
            var method = flag ? 'addClass' : 'removeClass';
            this.$blockInput[method]('text-muted');
            this.$editButton[method]('disabled');
            this.$dropButton[method]('disabled');
            return this;
        },

        /**
         *
         * @returns {TODORecord}
         */
        locked: function () {
            this.toggleLocked(true);
            return this;
        },

        /**
         *
         * @returns {TODORecord}
         */
        unlocked: function () {
            this.toggleLocked(false);
            return this;
        },

        /**
         *
         * @returns {boolean}
         */
        isLocked: function () {

            if (this.$data['lockAt'].isEmpty()) {
                return false;
            }

            return this.get('lockAt').getTime() > new Date().getTime()
                && this.get('lockBy') != this.context.identifier();
        },

        /**
         *
         */
        toggleInputGroup: function () {
            if (!this.isLocked()) {
                $('.todo-view', this.$container).toggleClass('d-none');
                $('.todo-change', this.$container).toggleClass('d-none');
            }
        },

        /**
         *
         * @param e
         */
        onCheck: function (e) {
            var value = $(e.target).prop('checked') ? 1 : 0;
            this.applyCheckedData(value);

            this.context.socket.send({
                operation: 'record.check',
                data: this.serialize()
            });
        },

        /**
         *
         * @param e
         */
        onDblClick: function (e) {
            if (!this.isLocked()) {
                this.toggleInputGroup();

                this.set('lockBy', this.context.identifier());
                this.set('lockAt', new Date(new Date().getTime() + 60000 * 10));

                this.context.socket.send({
                    operation: 'record.edit',
                    data: this.serialize()
                });
            }
        },

        /**
         *
         * @param e
         */
        onEdit: function (e) {
            if (!this.isLocked()) {
                this.toggleInputGroup();

                this.set('lockBy', this.context.identifier());
                this.set('lockAt', new Date(new Date().getTime() + 60000 * 10));

                this.context.socket.send({
                    operation: 'record.edit',
                    data: this.serialize()
                });
            }
        },

        /**
         *
         * @param e
         */
        onDrop: function (e) {
            if (!$(e.currentTarget).hasClass('disabled')) {
                this.context.socket.send({
                    operation: 'record.drop',
                    data: this.serialize()
                });
                this.context.store.removeRecord(this);
            }
        },

        /**
         *
         * @param e
         * @returns {*|void}
         */
        onKeyUp: function (e) {
            if (13 == e.keyCode) {
                this.onCommit(e);
            }

            // this.set('description', this.$editInput.val());
            // this.context.socket.send({
            //     operation: 'record.edit',
            //     data: this.serialize()
            // });
        },

        /**
         *
         * @param e
         */
        onCommit: function (e) {
            this.set('description', this.$editInput.val());
            this.set('lockAt', '');
            this.set('lockBy', '');
            this.refresh();

            this.context.socket.send({
                operation: 'record.commit',
                data: this.serialize()
            });

            this.toggleInputGroup();
        },

        /**
         *
         * @param e
         */
        onReject: function (e) {
            this.setData(this.origData);
            this.toggleInputGroup();

            this.context.socket.send({
                operation: 'record.reject',
                data: this.serialize()
            });
        },
    };

    /**
     *
     * @param options
     * @param context
     * @constructor
     */
    var TODOStore = function (options, context) {
        this.context = context;
        this.$options = $.extend({}, this.DEFAULTS, options);

        this.$cardContainer = $('.card', this.context.$container);
        this.$listContainer = $('.todo-list', this.context.$container);

        this.init();
    };

    TODOStore.prototype = {
        DEFAULTS: {
            records: []
        },

        init: function () {
            $.each(this.$options.data, $.proxy(function (index, data) {
                this.addData(data);
            }, this));

            this.context.socket.on('message', $.proxy(this.onMessage, this));
        },

        /**
         *
         * @param id
         * @returns {*}
         */
        getAt: function (id) {
            var index;

            $.each(this.$options.records, function (i, r) {
                if (r.get('id') === id) {
                    index = i;
                }
            });

            return this.$options.records[index];

        },

        /**
         *
         * @param record
         * @returns {*}
         */
        addRecord: function (record) {
            this.$options.records.push(record);

            this.$cardContainer.removeClass('d-none');
            this.$listContainer.prepend(record.$container);

            return record;
        },

        /**
         *
         * @param record
         */
        removeRecord: function (record) {

            var index;

            $.each(this.$options.records, $.proxy(function (i, r) {

                if (r.get('id') !== record.get('id')) {
                    return;
                }

                index = i;

            }, this));

            this.$options.records[index].$container.remove();
            this.$options.records.splice(index, 1);

            if (!this.$options.records.length) {
                this.$cardContainer.addClass('d-none');
            }
        },

        /**
         *
         * @param data
         * @returns {*}
         */
        addData: function (data) {
            return this.addRecord(new TODORecord(data, this.context));
        },

        /**
         *
         * @param msg
         */
        onMessage: function (msg) {
            var data = JSON.parse(msg.data);
            switch (data['operation']) {
                case 'record.add':
                    this.addRecord(new TODORecord(data['data'], this.context));
                    break;
                case 'record.check':
                    var record = this.getAt(data['data']['id']);
                    record.setData(data['data']);
                    record.refresh();
                    break;
                case 'record.edit':
                    var record = this.getAt(data['data']['id']);
                    record.setData(data['data']);
                    record.locked();
                    break;
                case 'record.commit':
                    var record = this.getAt(data['data']['id']);
                    record.setData(data['data']);
                    record.refresh();
                    record.unlocked();

                    break;
                case 'record.reject':
                    var record = this.getAt(data['data']['id']);
                    record.unlocked();
                    record.refresh();
                    break;
                case 'record.drop':
                    this.removeRecord(this.getAt(data['data']['id']));
                    break;
            }
        }
    };

    /**
     *
     * @param options
     * @param context
     * @constructor
     */
    var TODOForm = function (options, context) {
        this.context = context;
        this.$options = $.extend({}, this.DEFAULTS, options);
        this.$container = $(this.$options.container);
        this.$createInput = $('.form-control', this.$container);
        this.$limitBlock = $('.todo-limit', this.$container);

        this.init();
    };

    TODOForm.prototype = {
        DEFAULTS: {
            limit: 50
        },

        init: function () {
            this.$limitBlock.html(this.$options.limit);
            this.$createInput.on('keyup', $.proxy(this.onKeyUp, this));
            $('.todo-add', this.$container).on('click', $.proxy(this.onSubmit, this));
        },

        /**
         *
         * @returns {*}
         */
        getValue: function () {
            return this.$createInput.val();
        },

        /**
         *
         * @param value
         * @returns {TODOForm}
         */
        setValue: function (value) {
            this.$createInput.val(value);
            return this;
        },

        /**
         *
         * @returns {TODOForm}
         */
        clear: function () {
            this.setValue('');
            return this;
        },

        /**
         *
         * @param e
         */
        onKeyUp: function (e) {
            if (13 == e.keyCode) {
                return this.onSubmit(e);
            }

            if (this.$options.limit < this.getValue().length) {
                this.setValue(this.getValue().substr(0, this.$options.limit));
            }

            this.$limitBlock.html(this.$options.limit - this.getValue().length);
        },

        /**
         *
         * @param e
         */
        onSubmit: function (e) {
            var record = this.context.store.addData({
                description: this.getValue()
            });

            this.context.socket.send({
                operation: 'record.add',
                data: record.serialize()
            });

            this.clear();
        }
    };

    /**
     *
     * @constructor
     */
    var TODO = function () {
        // ... do something
    };

    TODO.prototype = {
        SESSION_KEY: 'identifier',
        DEFAULTS: {},

        /**
         *
         * @returns {string}
         */
        identifier: function () {
            /**
             *
             * @type {string}
             */
            var identifier = sessionStorage.getItem(this.SESSION_KEY);

            if (null === identifier) {
                identifier = broofa();
                sessionStorage.setItem(this.SESSION_KEY, identifier);
            }

            return identifier;
        },

        /**
         *
         * @param options
         */
        ready: function (options) {

            // var self = this, initializer = $.Deferred(function (deffered) {
            //     $(function () {
            //         deffered.resolve.call(self, deffered.options);
            //     });
            // });
            //
            // this.options = $.extend({}, this.DEFAULTS, options);
            //
            // $.each(this._init, function (name) {
            //
            //     if (name in self.options) {
            //         initializer.options = self.options[name];
            //         initializer.then(this, initializer.options);
            //     }
            // });

            this.identifier();

            this.$options = $.extend({}, this.DEFAULTS, options);
            this.$container = $(this.$options.container);

            $.each(this._init, $.proxy(function (name) {
                if (name in this.$options) {
                    this._init[name].call(this, this.$options[name]);
                }
            }, this));

        },

        _init: {
            /**
             *
             * @param options
             */
            socket: function (options) {
                this.socket = new TODOSocket(options, this);
            },
            /**
             *
             * @param options
             */
            store: function (options) {
                this.store = new TODOStore(options, this);
            },
            /**
             *
             * @param options
             */
            form: function (options) {
                this.form = new TODOForm(options, this);
            },

        }

    };

    if (!('TODO' in this)) this['TODO'] = new TODO;

}).call(this, window.jQuery);
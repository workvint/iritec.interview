$(document).ready(function(){
    var NewsModel = Backbone.Model.extend({
        defaults: {
            'date': '',
            'title': '',
            'content': ''
        }
    });
    
    var NewsView = Backbone.View.extend({
        tagName: 'a',
        className: 'list-group-item',
        template: _.template($('#news-template').html()),
    
        events: {
          'click': 'click' 
        },
    
        render: function() {
            return this.$el.append(this.template(this.model.toJSON()));
        },
        
        click: function() {
            this.model.trigger('reload', this.model);
        }
    });
    
    var NewsCollection = Backbone.Collection.extend({
        model: NewsModel,
        url: '/api/news'
    });
    
    var NewsCollectionView = Backbone.View.extend({
        el: 'div#content',
        
        initialize: function() {
            this.listenTo(this.collection, 'reload', this.reload);
            this.listenTo(this.collection, 'update', this.render);
            
        },
        
        render: function() {
            var self = this;
            
            this.$el.html('');
 
            this.collection.each(function(news) {
                var view = new NewsView({model: news});
                self.$el.append(view.render());
            });
        },
        
        reload: function(model) {
            this.$el.html('Загрузка...');
            
            this.collection.fetch({
                url: this.collection.url + '/' + model.id,
            });
            
            $('#options').hide();
            $('#bottom-crumbs').show();
        }
    });
    
    var AppView = Backbone.View.extend({
        el: 'body',
        
        initialize: function() {
            this.news = new NewsCollection();
            this.newsView = new NewsCollectionView({collection: this.news});
            
            this.news.fetch();
        },
        
        events: {
            'click button#test': 'test' 
        },
        
        test: function() {
            this.newsView.$el.html('Загрузка...');
            
            this.news.fetch({
                data: {
                    'page': $('input[name="page"]').val(),
                    'limit': $('input[name="limit"]').val(),
                    'sort': $('input[name="sort"]').val(),
                    'order': $('input[name="order"]').val()
                },
                
                success: function(collection) {
                    $('#result-count').html(collection.length);
                },
                
                error: function() {
                    $('#result-count').html(0);
                }
            });
        }
    });
    
    var App = new AppView();
});


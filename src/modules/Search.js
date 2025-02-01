import $ from 'jquery';

class search {
    // 1. create our object (ده المكان اللي بننشئ فيه الكائن او الكلاس)
    constructor() {
        this.addSearchHtml();
        this.resultDiv = $("#search-overlay__results");
        this.openButton = $(".js-search-trigger");
        this.closeButton = $(".search-overlay__close");
        this.searchOverlay = $(".search-overlay");
        this.searchField = $("#search-term");
        this.events();
        this.isOverlayOpen = false;
        this.isSpinnerVisible = false;
        this.previousValue;
        this.typingTimer;
    }

    // 2. events (هنا المكان اللي بحط فيه الاحداث  زي مثلا اني اضفط علي علامه البحث وهكذا)
    events() {
        this.openButton.on("click", this.openOverlay.bind(this));
        this.closeButton.on("click", this.closeOverlay.bind(this));
        $(document).on("keydown", this.keyPressDispatcher.bind(this));
        this.searchField.on("keyup", this.typingLogic.bind(this));
    }

    // 3. methods (هنا المكان اللي بحط فيه الافعال اللي المفروض الكائن ده يعملها زي مثلا لما اضغط علي البحث يفتحلي القايمه)
    typingLogic(){
        if(this.searchField.val() != this.previousValue) {
            clearTimeout(this.typingTimer);
            if(this.searchField.val()) {
                if(!this.isSpinnerVisible) {
                    this.resultDiv.html('<div class="spinner-loader"></div>');
                    this.isSpinnerVisible = true;
                }
                this.typingTimer = setTimeout(this.getResult.bind(this), 500);
            }else{
                this.resultDiv.html('');
                this.isSpinnerVisible = false;
            }
        }
        this.previousValue = this.searchField.val();  
    }

    getResult() {
        $.when(
            $.getJSON(universityData.root_url + '/wp-json/wp/v2/posts?search=' + this.searchField.val()),
            $.getJSON(universityData.root_url + '/wp-json/wp/v2/pages?search=' + this.searchField.val()),
            $.getJSON(universityData.root_url + '/wp-json/wp/v2/program?search=' + this.searchField.val())
    ).then((posts, pages, programs) => {
            var combinedResults = posts[0].concat(pages[0], programs[0]);
            this.resultDiv.html(`
                <h2 class="search-overlay__section-title">General Information</h2>
                ${combinedResults.length ? '<ul class="link-list min-list">' : '<p>No General Information Matches That Search</p>' }
                ${combinedResults.map(item => `<li><a href="${item.link}">${item.title.rendered}</a> ${item.type == 'post' ? `By ${item.authorName}` : `` } <li>`).join('')}
                ${combinedResults.length ? '</ul>' : ''}
                `);
            this.isSpinnerVisible = false;
        }, () => {
            this.resultDiv.html('<p>Unexpected error; Please try again.</p>');
        })
    }

    keyPressDispatcher(e) {
        if ((e.key === "s" || e.key === "S") && !this.isOverlayOpen && !$('input, textarea').is(':focus')) {
            this.openOverlay();
            console.log('this is open massage');
        }
        if(e.key === "Escape" && this.isOverlayOpen) {
            this.closeOverlay();
            console.log('this is close massage');
        }
    }
    
    openOverlay() {
        this.searchOverlay.addClass("search-overlay--active");
        $("body").addClass("body-no-scroll");
        this.searchField.val('');
        setTimeout(() => this.searchField.focus(), 301);
        this.isOverlayOpen = true;
    }

    closeOverlay() {
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass("body-no-scroll");
        
        this.isOverlayOpen = false;

    }

    addSearchHtml() {
        $('body').append(`
    <div class="search-overlay">
        <div class="search-overlay__top">
            <div class="container">
                <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term">
                <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
            </div>
        </div>

        <div class="container">
            <div id="search-overlay__results"></div>
        </div>
    </div>
        `)
    }

}

export default search
import $ from 'jquery';

class search {
    // 1. create our object (ده المكان اللي بننشئ فيه الكائن او الكلاس)
    constructor() {
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
                this.typingTimer = setTimeout(this.getResult.bind(this), 2000);
            }else{
                this.resultDiv.html('');
                this.isSpinnerVisible = false;
            }
        }
        this.previousValue = this.searchField.val();
    }

    getResult() {
        this.resultDiv.html("imagine real search results here...");
        this.isSpinnerVisible = false;
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
        this.isOverlayOpen = true;
    }

    closeOverlay() {
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass("body-no-scroll");
        this.isOverlayOpen = false;

    }
}

export default search
window.addEventListener('DOMContentLoaded', (e)=>{
    //tagify. Reference: https://github.com/yairEO/tagify#ajax-whitelist
    // const tagInput = document.querySelector('#invite');
    const tagInput = document.getElementById('invite');
    var tagify = new Tagify(tagInput,{enforceWhitelist: true});
    var controller;

    // listen to keystrokes
    tagify.on('input', onInput);

    function onInput(e){
        // console.log('input detected.')
        let value = e.detail.value;
        if(value == "") {tagify.whitelist = []; return false};
        tagify.whitelist = null //reset the whitelist

        controller && controller.abort()
        controller = new AbortController()

        //show loading animation and hide the suggestions dropdown
        tagify.loading(true).dropdown.hide()

        fetch(window.location.host+'/api/userlist/uid='+uid+'&input='+value, {signal:controller.signal})
            .then(RES => RES.json())
            .then(function(newWhitelist){
                tagify.whitelist = newWhitelist //update the whitelist
                tagify.loading(false).dropdown.show(value)  //render the suggestions dropdown
            })
    }
})

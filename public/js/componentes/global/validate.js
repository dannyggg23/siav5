(function() {
    webshim.setOptions('forms', {
      lazyCustomMessages: true,
      iVal: {
        //add config to find right wrapper
        fieldWrapper: '.form-group',

        //add bootstrap specific classes
        errorMessageClass: 'help-block',
        successWrapperClass: 'has-success',
        errorWrapperClass: 'has-error',

        //general iVal cfg
        sel: '.ws-validate',
        handleBubble: 'hide' // hide error bubble
      }
    });

    //load forms polyfill + iVal feature
    webshim.polyfill('forms');
  })();

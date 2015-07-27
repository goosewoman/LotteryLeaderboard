var usernames = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    limit: 10,
    prefetch: {
// url points to a json file that contains an array of country names, see
// https://github.com/twitter/typeahead.js/blob/gh-pages/data/countries.json
        url: '/lottery/userlist.php',
// the json file contains an array of strings, but the Bloodhound
// suggestion engine expects JavaScript objects so this converts all of
// those strings
        filter: function(list) {
            return $.map(list, function(username) { return { name: username }; });
        }
    }
});

// kicks off the loading/processing of `local` and `prefetch`
usernames.initialize();
$(".typeahead").val("");
// passing in `null` for the `options` arguments will result in the default
// options being used
$('.typeahead').typeahead(null, {
    name: 'Usernames',
    displayKey: 'name',
// `ttAdapter` wraps the suggestion engine in an adapter that
// is compatible with the typeahead jQuery plugin
    source: usernames.ttAdapter()
});

$('.typeahead').bind('typeahead:selected', function(obj, datum, name) {
    // outputs, e.g., {"type":"typeahead:selected","timeStamp":1371822938628,"jQuery19105037956037711017":true,"isTrigger":true,"namespace":"","namespace_re":null,"target":{"jQuery19105037956037711017":46},"delegateTarget":{"jQuery19105037956037711017":46},"currentTarget":
   var username = datum.name // contains datum value, tokens and custom fields
    // outputs, e.g., {"redirect_url":"http://localhost/test/topic/test_topic","image_url":"http://localhost/test/upload/images/t_FWnYhhqd.jpg","description":"A test description","value":"A test value","tokens":["A","test","value"]}
    // in this case I created custom fields called 'redirect_url', 'image_url', 'description'

    // outputs, e.g., "my_dataset"
    window.location.href = "/lottery/user/"+username;
});
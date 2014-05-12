

<script>
 jQuery.getFeed({
   url: 'rss.xml',
   success: function(feed) {
     alert(feed.title);
   }
 });
 </script>
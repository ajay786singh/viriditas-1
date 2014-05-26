<?php /* Template Name: Search */ ?>

<?php get_header() ?>
<div class="span-12">
  <script>
    (function() {
      var cx = '012883476298758501389:gdx0drwv2wo';
      var gcse = document.createElement('script');
      gcse.type = 'text/javascript';
      gcse.async = true;
      gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
          '//www.google.com/cse/cse.js?cx=' + cx;
      var s = document.getElementsByTagName('script')[0];
      s.parentNode.insertBefore(gcse, s);
    })();
  </script>
  <gcse:searchresults-only noResultsString="Sorry, we coudn't find any posts containing &#8216;<?php echo$_REQUEST["q"]; ?>&#8217;."</gcse:searchresults-only>
</div>


<?php get_footer() ?>

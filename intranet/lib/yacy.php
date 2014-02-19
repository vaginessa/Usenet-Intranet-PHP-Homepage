<script src="<?php echo $config['yacyUrl']; ?>/jquery/js/jquery-1.7.min.js" type="text/javascript" type="text/javascript"></script>
<script>                        
<!--
        $(document).ready(function() {
                yconf = {
                        url      : '<?php echo $config['yacyUrl']; ?>',
                        title    : 'YaCy Search Widget',
                        logo     : '/yacy/yacy/ui/img/yacy-logo.png',
                        link     : 'http://www.yacy.net',
                        global   : true,
                        width    : 500,
                        height   : 600,
                        position : ['top',30],
                        theme    : 'smoothness'
                };
                $.getScript(yconf.url+'/portalsearch/yacy-portalsearch.js', function(){});
        });
        -->
</script>
&nbsp;
<div id="yacylivesearch" align="right">
        <form id="ysearch" method="get" accept-charset="UTF-8" action="<?php echo $config['yacyUrl']; ?>/yacysearch.html">
                Live Search <input name="query" id="yquery" class="fancy" type="text" size="15" maxlength="80" value=""/>
                <input type="hidden" name="verify" value="cacheonly" />
                <input type="hidden" name="maximumRecords" value="20" />
                <input type="hidden" name="resource" value="local" />
                <input type="hidden" name="urlmaskfilter" value=".*" />
                <input type="hidden" name="prefermaskfilter" value="" />
                <input type="hidden" name="display" value="2" />
                <input type="hidden" name="nav" value="all" />
                <input type="submit" name="Enter" value="Search" />
        </form>
</div>


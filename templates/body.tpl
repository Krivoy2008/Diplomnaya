{include file="header.tpl"}
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Test Manager</a>
        </div>
        <!--<div id="navbar" class="collapse navbar-collapse">-->
        <!--<ul class="nav navbar-nav">-->
        <!--<li class="active"><a href="#">Home</a></li>-->
        <!--<li><a href="#about">About</a></li>-->
        <!--<li><a href="#contact">Contact</a></li>-->
        <!--</ul>-->
        <!--</div>&lt;!&ndash;/.nav-collapse &ndash;&gt;-->
    </div>
</nav>

<div class="container">

    <div class="starter-template">
        <h3 id="number"></h3>
        <div id="test_wrapper">
            <ul id="test_list">
                {foreach from=$questions item=test}
                    {include file="test.tpl" tests=$test}
                {/foreach}
            </ul>
        </div>
        <button type="button" id="next" class="btn btn-primary" style="float: right">Next</button>
        <button type="button" id="prev" class="btn btn-primary" style="float: left">Prev</button>
    </div>

</div><!-- /.container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
{include file="footer.tpl"}


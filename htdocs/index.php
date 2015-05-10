<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appizy</title>
    <link rel="icon" href="favicon.ico" type="image/gif">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="page-header">
        <h1>Appizy</h1>

        <p class="lead">Create dynamic webcontent starting from a simple spreadsheet!</p>
    </div>

    <div role="tabpanel">

        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#tab-appizy" aria-controls="tab-appizy" role="tab"
                                                      data-toggle="tab">Appizy</a></li>
            <li role="presentation"><a href="#tab-faq" aria-controls="tab-faq" role="tab" data-toggle="tab">FAQ</a>
            </li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="tab-appizy">
                <div class="row" style="padding-top:10px;">
                    <div class="col-xs-12">
                        <p>
                            <button id="uploadBtn" class="btn btn-large btn-primary">Choose File</button>
                            &nbsp;&nbsp;<span id="status"></span>
                        </p>
                    </div>
                    <div class="col-xs-12">
                        <div id="progressOuter" class="progress progress-striped active" style="display:none;">
                            <div id="progressBar" class="progress-bar progress-bar-success" role="progressbar"
                                 aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div id="output" class="well" style="overflow-y: scroll;height:240px;">
                            <div class="text-muted">
                                <ul>
                                    <li>Choose a OpenDocument (.ods) spreadsheet on your computer or download a <a
                                            href="demo-files/demo_appizy.ods">demo file</a> just made for you!
                                    </li>
                                    <li>If you are using Excel, first convert your file to OpenDocument using <a
                                            href="https://cloudconvert.com/" target="_blank">CloudConvert</a>
                                    </li>
                                    <li>Your original file will deleted automatically just after the conversion
                                        process.
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div id="result">
                        </div>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="tab-faq">
                <h4>What is the biggest file size Appizy can convert?</h4>

                <p>You might encounter an error message similar to <code>File too big</code>. This happens when
                    the algorithm of Appizy exhausts the PHP resource allowed by the server. In other words, you are
                    asking too much...</p>

                <p>
                    With a classic PHP configuration this limit is observed around 20 000 cells. This is the case on
                    server currently hosting this webpage.
                </p>

                <p class="bs-callout bs-callout-info">
                    To convert more important file you can download the code Appizy on your own machine and pimp the PHP
                    configuration with more memory. Have a look at this discussion to learn how to do this: <a
                        href="http://stackoverflow.com/questions/3534274/php-memory-exhausted" target="_blank">
                    http://stackoverflow.com/questions/3534274/php-memory-exhausted</a>
                </p>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h4>How does this works?</h4>

            <p>Appizy converts your spreadsheet into a web-calculator that you can share and reuse on Internet. Create
                your
                calculator with your favorite spreadsheet software. Appizy supports XLS, XLSX and ODS files.</p>

            <p>Appizy loves HTML, Javascript and CSS. Just like you. Let's keep things simple. Appizy provides friendly
                clean code you can edit afterward.</p>

            <h4>I need inspiration</h4>

            <p>Here is a <a href="demo-files/demo_appizy.ods">simple spreadsheet</a>. Just download it and have a look
                at it. Then give it back to Appizy and look at the result: a simple HTML, CSS and Javascript twin of the
                original file.</p>

            <p></p>

            <h4>Can I participate?</h4>

            <p>You can contribute to Appizy in many different ways:
            <ul>
                <li>Report bugs, propose features directly on <a href="https://github.com/nicolashefti/appizy/issues"
                                                                 target="_blank">the project repository</a>
                </li>
                <li>Propose improvement for the text on this page (I'm not native English speaker)</li>
                <li><a href="https://github.com/nicolashefti/appizy/fork" target="_blank">Fork the repository</a> and
                    make a pull request
                </li>
                <li>Just simply drop me an email: contact (at) appizy.com</li>
            </ul>
            </p>

            <h4>License</h4>

            <p>Appizy is released under the terms of the MIT license</p>
        </div>
    </div>
    <hr>
    <p>Project by <a href="https://fr.linkedin.com/pub/nicolas-hefti/24/67b/121" target="_blank">Nicolas Hefti</a>,
        coded with fun & happiness in Cologne
    </p>
</div>
<script type="text/javascript" src="js/appizy-console.js"></script>
<script type="text/javascript" src="js/SimpleAjaxUploader.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<?php if (getenv('GA_ID')): ?>
    <script type="application/javascript">
        (function(window, $){
            $(window).on('appizy', function(event, event_name){
                window.ga('send','event','appizy', 'conversion', 'start');
            });
        })(window, jQuery);

        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', '<?php echo getenv('GA_ID') ?>', 'auto');
        ga('send', 'pageview');
    </script>
<?php endif ?>
</body>
</html>

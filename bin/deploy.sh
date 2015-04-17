#!/bin/bash

replace '</body>' '<script type="application/javascript" src="/js/appizy-analytics.js"></script></body>' -- htdocs/index.html

(
  echo "
  put htdocs vhosts/appizy.com/*
  put src    vhosts/*
  quit
    "
) | sftp 243314@sftp.dc0.gpaas.net


replace '<script type="application/javascript" src="/js/appizy-analytics.js"></script></body>' '</body>' -- htdocs/index.html
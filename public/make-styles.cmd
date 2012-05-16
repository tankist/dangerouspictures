@ECHO OFF

lessc css\bootstrap\publisher.less > css\publisher.css && cleancss css\publisher.css > css\publisher.min.css && lessc css\bootstrap\admin.less > css\admin.css && cleancss css\admin.css > css\admin.min.css
sprhide
=======

Use CSS sprite technique to hide image source (via PHP and Javascript)

This technique puts a major roadblock in the way of those trying to download your images online. The downloader will either need to guess the original filename or use image editing software to reproduce the image. (Of course, I'm probably missing something that will make it easy to subvert.)

Check out the example (test.php) for usage.

It consists of a single PHP class. The constructor takes three arguments:

id - could be anything, preferably not the same as your filename
filename - the image to use
row - the number of rows to use in the scrambling of the image (this defaults to 20)
The PHP creates a new image in the current directory, so your server must have write access to this directory.

There are many ways to enhance this. I just wanted to get the idea out into the community and see if it gathers any interest.

Oh - if you want to see it in action: http://benburleson.com/sprhide/test.php

Art DB.

This is a project that I solo developed for Database Systems course at NDSU during the Fall 2018 semester.

It is an art print database webapp build with HTML, PHP, some CSS, and an Oracle SQL database. The idea behind it was to create an
application for myself to use organize the different types of prints I have, as well as keep track of how many of each I have.

The assignment did not require you to have any sort of image uploading, so that was something I implemented later because I felt like it
was a good idea. I also found ways with CSS and HTML to display thumbnails of the images when hovering over <a> tags, which was really
helpful for a preview of how things should look.
 
This project does not work any more due to no longer having access the CSCI Linux Lab, so my SQL queries do not function since it cannot 
authenticate my credentials due to them being invalid. I would like to learn how to make my own Apache Oracle SQL server so that I could
configure this to work again, because I do think it was a really helpful tool for myself.

A few notes:
- The image uploading was not done directly through the DBMS. Instead, they were uploaded directly to the server and the SQL query would 
  store the images URL in lieu of the actual image itself. If I had more time I would have pursued how to upload images as BLOB types, but
  due to my limited knowledge as well I settled for this way.

- We were not required to have any sort of user authentication, and due to limited knowledge of this as well as time restraints, this was
  not implemented. If I were to get this to work again, I would have to implement that.
  
- I wrote all of this code by hand, with no auto-complete. The text editor I wrote this in was the built-in text editor that comes with 
  WinSCP. I decided to do it this way as a challenge to myself and it taught me a lot about how careful you need to be with syntax and 
  making sure you close all your tags right. :)
  
- The CSS portion of this is something I found with the help of w3 schools, with our projects we had no limits on the types of resources 
  we could use to help us. So, I referenced their guides a lot.
  
- We were also given a basic rundown of how SQL injecting works and how it can ruin your SQL tables and data stored. So, with what we were 
  taught, I implemented a basic means to prevent that through form validation and checking for special characters.

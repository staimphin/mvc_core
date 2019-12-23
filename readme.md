Simple MVC core Framework

1) What is MVC?

MVC stands for Model–View–Controller.

Details can be  found at the following adrees:
https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller

According to wikipedia, the descriptions of components are:

Model:
    The central component of the pattern. 
    - It is the application's dynamic data structure, independent of the user interface.
    - It directly manages the data, logic and rules of the application.

In other words, most of the code should be inside the Model files.

View
   - Any representation of information such as a chart, diagram or table. Multiple views of the same information are possible, such as a bar chart for management and a tabular view for accountants.

In other words, UI related stuff.

Controller
    Accepts input and converts it to commands for the model or view.

 In other words, a controller's job is to pass data to view and / or model.


2) Why this project?
There's plenty of MVC frameworks, like CakePHP or Laravel, and I use them a lot but I wanted to understand how MVC really works behind the scene.
Also, I noticed many people ask "how to do something in the cakePHP / Laravel ways", that's great but who really knows what those frameworks are doing?

The purpose of this project is to learn how a simple CRUD framework works:
 - classes autoloader
 - routing
 - errors and exceptions handling
 - name spaces

Synalytic
=============

Version: 0.1.0<br />
Pre-release organizational commit<br />
Author: Ray Sarno<br />

INTRODUCTION
-------------

Though only in it's early stages of development, Synalytic aims to simplify the process of building data driven sites by providing a simple, robust, and highly customizable set of tools for building database driven sites.  Synalytic takes a holistic approach, with consideration given to all aspects of integrating server, database, backend, and frontend.  The ultimate result will be a framework that makes it easy to build entire systems for making data both productive and pretty.  Everything from database efficiency to SEO to the fluidity of UI animations will be taken into account by Synalytic.   

Synalytic is a PHP framework that bridges data and websites, giving developers a set of tools to make features such as data importation, modification, visualization, and analytics easy.  It's more than a DBMS.  It's more than a CMS.  It's Synalytic.


AUTHOR'S NOTE
-------------

Synalytic spawned from the realization that many individual components from individual projects of mine could be generalized to create a widely useful and applicable framework.  As such, Synalytic is somewhat disjointed at this point.  Currently, many components and files contain instances where specific nomenclature or file / variable / database references remain as relics from the projects in which they were birthed.  I am working to update and generalize the components to form a more cohesize whole, welding each component to the framework, moving certain functions / classes to the core when it makes sense before buffing out the marks fo my work for a smooth and shiny finish.


TERMS OF USE 
-------------

The MIT License (MIT)
(via github.com and choosealicense.com)

Copyright (c) 2014 Ray Sarno

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.


ACKNOWLEDGMENTS
-------------

Thanks to the team responsible for developing PHPExcel, a great set of classes for handling spreadsheet files with PHP. 
https://phpexcel.codeplex.com/ 


INSTALLATION
-------------

1. Copy entire repository to root web directory
2. Edit /core/config.php with your MySQL database information (host, user, pass, db name)


DIRECTORY INFO
-------------

<table>
	<tr>
		<td>
			/
		</td>
		<td>
			contains frontend page files and five required php files (index, header, footer, login, logout) (may still contain backend / functional files in this version)
		</td>
	</tr>
	<tr>
		<td>
			/core
		</td>
		<td>
			core framework files; the contents of this directory are either essential or widely used
		</td>
	</tr>
	<tr>
		<td>
			/components
		</td>
		<td>
			contains directories for the componenets that power more specific functionality of Synalytic
		</td>
	</tr>
	<tr>
		<td>
			/unmigrated
		</td>
		<td>
			files and components from previous projects that have not yet been integrated seamlessly into Synalytic
		</td>
	</tr>
	<tr>
		<td>
			/content
		</td>
		<td>
			contains framework instance specific content used / generated by the installation (also core image files)
		</td>
	</tr>
	<tr>
		<td>
			/log
		</td>
		<td>
			contains directories for debug logs as well as activity / usage / tracking logs
		</td>
	</tr>
</table>

SYNALYTIC IN ACTION: SCREENSHOTS FROM PREVIOUS PROJECTS
-------------
![alt tag](https://raw.githubusercontent.com/raysarno/synalytic/master/screenshots/Analytics2.jpg)
![alt tag](https://raw.githubusercontent.com/raysarno/synalytic/master/screenshots/Analytics1.jpg)
![alt tag](https://raw.githubusercontent.com/raysarno/synalytic/master/screenshots/CRM1.jpg)
![alt tag](https://raw.githubusercontent.com/raysarno/synalytic/master/screenshots/CRM2.jpg)
![alt tag](https://raw.githubusercontent.com/raysarno/synalytic/master/screenshots/CRM3.jpg)


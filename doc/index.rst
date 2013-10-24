*****************************************
Readme / Documentation for `MyLabStocks`
*****************************************


`MyLabStocks` is a web application allowing to easily store, share and retrieve 
information about molecular biology materials stored in a laboratory. 

License
=======

Copyright CNRS 2012-2013                                                 
                                                                          
- Florent CHUFFART                                                         
- Gael YVERT                                                               
                                                                          
This software is a computer program which purpose is to perform quantitative analysis of epigenetic marks at single nucleosome resolution.     
                                                                          
The Software is provided “as is” without warranty of any kind, either express or implied, including without limitation any implied warranties of condition, uninterrupted use, merchantability, fitness for a particular purpose, or non-infringement. You use this software at your own risk.

This software is governed by the CeCILL license under French law and abiding by the rules of distribution of free software.  You can  use, modify and/ or redistribute the software under the terms of the CeCILL license as circulated by CEA, CNRS and INRIA at the following URL "http://www.cecill.info".                                                
                                                                          
As a counterpart to the access to the source code and  rights to copy, modify and redistribute granted by the license, users are provided only  with a limited warranty  and the software's author,  the holder of the economic rights,  and the successive licensors  have only  limited liability.                                                               
                                                                          
In this respect, the user's attention is drawn to the risks associated with loading,  using,  modifying and/or developing or reproducing the software by the user in light of its specific status of free software, that may mean  that it is complicated to manipulate,  and  that  also therefore means  that it is reserved for developers  and  experienced professionals having in-depth computer knowledge. Users are therefore encouraged to load and test the software's suitability as regards their requirements in conditions enabling the security of their systems and/or data to be ensured and,  more generally, to use and operate it in the same conditions as regards security.                                     
                                                                          
The fact that you are presently reading this means that you have had knowledge of the CeCILL license and that you accept its terms.           

Installation Instructions
=========================

This installation has been fully tested on:
  -  Debian 7.2.0 amd64 netinst [1], running on virtual machine using Oracle VM VirtualBox [2] for macosx (dev)
  -  Ubuntu Server 12.04.3 LTSUbuntu server LTS [3], running on physical machine (prod)
  
[1] http://cdimage.debian.org/debian-cd/7.2.0/amd64/iso-cd/debian-7.2.0-amd64-netinst.iso

[2] https://www.virtualbox.org

[3] http://www.ubuntu.com/download/server

Prerequisites
-------------

.. code:: bash

  sudo apt-get install git mysql-server apache2 php5 phpmyadmin php5-curl csh blast2 tomcat6 ant openjdk-6-jdk 
..


Get MyLabStocks Sources
------------------------

.. code:: bash

  git clone http://forge.cbp.ens-lyon.fr/git/mylabstocks
..


Install wwwBlast
----------------

MyLabStocks is distributed with wwwblast working on a x64 architecture.
For other architectures refer to the NCBI repositories
http://mirrors.vbi.vt.edu/mirrors/ftp.ncbi.nih.gov/blast/executables/release/LATEST


.. code:: bash

  cd mylabstocks/opts/
  tar xfvz wwwblast-2.2.26-x64-linux.tar.gz
  sudo cp -r blast /var/www/.
  sudo chown www-data:www-data /var/www/blast/TmpGifs /var/www/blast/*.log /var/www/blast/db/
  echo "<Directory /var/www/blast/>" > /tmp/blast.conf 
  echo "   Options +ExecCGI" >> /tmp/blast.conf 
  echo "</Directory>" >> /tmp/blast.conf 
  echo "AddHandler cgi-script .cgi" >> /tmp/blast.conf 
  sudo cp /tmp/blast.conf /etc/apache2/conf.d/blast.conf 
  rm /tmp/blast.conf
  sudo /etc/init.d/apache2 restart
  sudo sed -i 's/<option VALUE.*test_na_db/<option VALUE=oligostock_db>oligostock_db<option VALUE=plasmidstock_db>plasmidstock_db<option VALUE=plfeatstock_db>plfeatstock_db/g' /var/www/blast/blast.html
  sudo sed -i 's/<option VALUE.*test_aa_db//g' /var/www/blast/blast.html
  cat /var/www/blast/blast.rc > /tmp/blast.rc
  echo "blastn oligostock_db" >> /tmp/blast.rc
  echo "tblastn oligostock_db" >> /tmp/blast.rc
  echo "tblastx oligostock_db" >> /tmp/blast.rc
  echo "blastn plasmidstock_db" >> /tmp/blast.rc
  echo "tblastn plasmidstock_db" >> /tmp/blast.rc
  echo "tblastx plasmidstock_db" >> /tmp/blast.rc
  echo "blastn plfeatstock_db" >> /tmp/blast.rc
  echo "tblastn plfeatstock_db" >> /tmp/blast.rc
  echo "tblastx plfeatstock_db" >> /tmp/blast.rc
  sudo cp /tmp/blast.rc /var/www/blast/blast.rc
  rm /tmp/blast.rc
  cd ../..
..


Now you have a wwwBlast instance available here: http://your_server/blast.


Install PlasMapper
------------------

.. code:: bash

  cd mylabstocks/opts/
  tar xfvz PlasMapper_download.tar.gz
  cd PlasMapper
  # modify installdir as /var/lib/tomcat6
  sed -i 's/\/home\/tomcat/\/var\/lib\/tomcat6/g' build.xml 
  # modify servletjar as /usr/share/tomcat6/lib/servlet-api.jar
  sed -i 's/${installdir}\/common\/lib\/servlet-api.jar/\/usr\/share\/tomcat6\/lib\/servlet-api.jar/g' build.xml 
  # change /home/tomcat for /var/lib/tomcat6
  sed -i 's/\/home\/tomcat/\/var\/lib\/tomcat6/g' src/ca/ualberta/xdong/plasMapper/annotate/plasMapConfiguration_en_CA.properties
  # and set blastallDir=/usr/bin/
  sed -i 's/\/usr\/local\/bin\//\/usr\/bin\//g' src/ca/ualberta/xdong/plasMapper/annotate/plasMapConfiguration_en_CA.properties
  # Here PlasMapper is ready to be configured

  ant clean
  ant build 
  sudo ant install 
  sudo rm -Rf /var/lib/tomcat6/webapps/PlasMapper/tmp
  sudo ln -s /tmp/tomcat6-tomcat6-tmp/ /var/lib/tomcat6/webapps/PlasMapper/tmp
  echo '<?xml version="1.0" encoding="UTF-8"?><Context path="/myapp" allowLinking="true"></Context>' > context.xml
  sudo mv context.xml /var/lib/tomcat6/webapps/PlasMapper/META-INF/
  sudo /etc/init.d/tomcat6 restart
  # Here PlasMapper works on your server at the url http://myserver:8080/PlasMapper

  sudo chown root:www-data /var/lib/tomcat6/webapps/PlasMapper/dataBase/db_vectorFeature/*.*
  sudo chmod 664 /var/lib/tomcat6/webapps/PlasMapper/dataBase/db_vectorFeature/*.*
  sudo chown root:www-data /var/lib/tomcat6/webapps/PlasMapper/dataBase/db_vectorFeature/
  sudo chmod 775 /var/lib/tomcat6/webapps/PlasMapper/dataBase/db_vectorFeature/
  sudo chown root:www-data /var/lib/tomcat6/webapps/PlasMapper/html/feature.html
  sudo chmod 664 /var/lib/tomcat6/webapps/PlasMapper/html/feature.html
  # Here plasmids feature could be blasted by MyLabStock

  cd ../../..
..

Now you have a PlasMapper instance available here: http://your_server:8080/PlasMapper.


Install MyLabStocks
-------------------

.. code:: bash

  sudo cp -r mylabstocks/src /var/www/labstocks
  sudo rm /var/www/labstocks/install_db.phpsh
  sudo touch /var/www/labstocks/formatdb.log
  sudo chmod 440 /var/www/labstocks/connect_entry.php 
  sudo mkdir /var/www/labstocks/plasmid_files 
  sudo mkdir /var/www/labstocks/raw_dirs
  sudo mkdir /var/www/labstocks/collections
  sudo chown -R www-data:www-data /var/www/labstocks/connect_entry.php /var/www/labstocks/formatdb.log  /var/www/labstocks/plasmid_files /var/www/labstocks/raw_dirs
..

Configure MyLabStocks
---------------------

Now you have to update connexion params in /var/www/labstocks/connect_entry.php

.. code:: bash

  sudo vi /var/www/labstocks/connect_entry.php 
..

finally run the following script to  define passwords and instantiate db.

.. code:: bash

  php mylabstocks/src/install_db.phpsh 
..

Congratulation your LabStocks instance is available here: http://your_server/labstocks.

Go to http://your_server/labstocks/wwwblast.php to initialize blast dbs.

Links
-----

`MyLabStocks` home, repository and documentation: http://forge.cbp.ens-lyon.fr/redmine/projects/mylabstocks

Gael Yvert lab: http://www.ens-lyon.fr/LBMC/gisv/





Usage
=====

Advanced Search
---------------

In the strain section, the form `Search in genotype` filters strains where the 
fields `locus1`, `locus2`, `locus3`, `locus4`, `locus5`, `ADE2`, `HIS3`, `LEU2`, 
`LYS2`, `MET15`, `TRP1`, `URA3`, `HO_`, `Cytoplasmic_Character` or 
`extrachromosomal_plasmid` contain the given expression. This filter is case 
insensitive.

Plasmids Sequences Management
-----------------------------

Even in edit mode, users are not granted permission to directly edit a plasmid 
sequence, nor the URL to the plasmid sequence file. If a new sequence must be 
entered instead of the current one, users must upload a new sequence file, in 
.gb or .gb.gz format. MyLabStocks then automatically reads the file and update 
the sequence field and the URL. This ensures consistency between URL, sequence 
and the file itself.







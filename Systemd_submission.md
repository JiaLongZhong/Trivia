<table><tr><td> <em>Assignment: </em> IT490 - Systemd Services</td></tr>
<tr><td> <em>Student: </em> Dominic Quitoni(dwq2)</td></tr>
<tr><td> <em>Generated: </em> 6/29/2022 8:55:40 PM</td></tr>
<tr><td> <em>Grading Link: </em> <a rel="noreferrer noopener" href="https://learn.ethereallab.app/homework/IT490-451-M22/it490-systemd-services/grade/dwq2" target="_blank">Grading</a></td></tr></table>
<table><tr><td> <em>Instructions: </em> <ol><li>As a group, create a system service for your DB MQ Consumer and API MQ Consumer that does the following:<br></li><ol><li>Starts the related consumers to run in the background</li><li>Starts when the system is rebooted</li><li>Starts only if/when the network is available&nbsp;(since you can't check the mq service since it's on a different machine)</li><li>Restarts the process (or child processes) if they fail or are terminated abruptly</li></ol><li>Each related .service file should be uploaded to your repo under an appropriate file name</li><li>Fill in the below evidence</li><ol><li>Note: since there are two files not everyone on the team will have direct hands on experience</li><ol><li>They could create an arbitray server-side file and setup a test service for that and include it in the below deliverables, just clearly mention it's a test file</li></ol></ol><li>Create a systemd_submission.md file in the related branch filled with the below deliverable's output</li><li>Add/commit/push it to the necessary branch</li><li>Submit the direct link to the md file from the specific branch on the team github to Canvas</li></ol></td></tr></table>
<table><tr><td> <em>Deliverable 1: </em> Service files </td></tr><tr><td><em>Status: </em> <img width="100" height="20" src="http://via.placeholder.com/400x120/009955/fff?text=Complete"></td></tr>
<tr><td><table><tr><td> <em>Sub-Task 1: </em> Screenshots and evidence of service files</td></tr>
<tr><td><table><tr><td><img width="768px" src="https://user-images.githubusercontent.com/70656707/176564953-cce89dda-2109-4301-94d6-8c60cc7699dd.png"/></td></tr>
<tr><td> <em>Caption:</em> <p>Service for restarting the Login Consumers as saj39 , this is for the<br>DB server<br></p>
</td></tr>
<tr><td><img width="768px" src="https://user-images.githubusercontent.com/70656707/176565116-4e2e8a20-01ec-4686-8c28-0c5828bb4aac.png"/></td></tr>
<tr><td> <em>Caption:</em> <p>Service for restarting the Register Consumer as saj39 , this is for the<br>DB server<br></p>
</td></tr>
<tr><td><img width="768px" src="https://user-images.githubusercontent.com/70656707/176565184-c645d302-e56b-4d92-99ad-85bac0402097.png"/></td></tr>
<tr><td> <em>Caption:</em> <p>Service for restarting the update profile Consumer as saj39 , this is for<br>the DB server<br></p>
</td></tr>
</table></td></tr>
<tr><td> <em>Sub-Task 2: </em> Property explanation</td></tr>
<tr><td> <em>Response:</em> <p>The registeruser service starts up after the service is able to target a<br>network. It simply restarts after 1 sec of being down under the permission<br>of the given user, saj39, to run rpc_register_consumer.php consumer file, allowing new users<br>to be writen from DB to be authenticated<div>The updateuser service starts up after<br>the service is able to target a network. It simple restarts after 1<br>sec of being down under the permission of the given user, saj39, to<br>run rpc_update_consumer.php consumer file to allow it to collect the requests and data<br>to update the DB with.<br></div><div>The loginuser service starts up after the service is<br>able to target a network. It simply restarts after 1 sec of being<br>down under the permision of the given user, saj39, to run rpc_login_consumer.php consumer<br>file to allow users to authenticate and login to the website.<br></div><div><br></div><div>we set a<br>simple unit and declared that the service should be launched after network.target. in<br>the [Service] we set the type to simple, and to restart after 1<br>sec.&nbsp; and then to execute the respective consumer. [install] is where we decalred<br>that it gets included in multi-user target</div><div>&nbsp;</div><br></p><br></td></tr>
</table></td></tr>
<table><tr><td> <em>Deliverable 2: </em> Evidence </td></tr><tr><td><em>Status: </em> <img width="100" height="20" src="http://via.placeholder.com/400x120/009955/fff?text=Complete"></td></tr>
<tr><td><table><tr><td> <em>Sub-Task 1: </em> Add screenshot snippets of logs from systemctl and jounralctl of the process running</td></tr>
<tr><td><table><tr><td><img width="768px" src="https://user-images.githubusercontent.com/70656707/176563613-1555ad51-4c28-4503-9305-841844063229.png"/></td></tr>
<tr><td> <em>Caption:</em> <p>this image is showing the journalctl log of consumer running then the service<br>restarting it<br></p>
</td></tr>
</table></td></tr>
<tr><td> <em>Sub-Task 2: </em> Add screenshots showing the process restarting after a failure or termination</td></tr>
<tr><td><table><tr><td><img width="768px" src="https://user-images.githubusercontent.com/67155481/176242009-c33e6c98-b841-444b-915d-7088a85bc2d6.png"/></td></tr>
<tr><td> <em>Caption:</em> <p>This image shows the service restarting after a failure. seen by the PID<br>being different<br></p>
</td></tr>
</table></td></tr>
</table></td></tr>
<table><tr><td> <em>Deliverable 3: </em> Discussion </td></tr><tr><td><em>Status: </em> <img width="100" height="20" src="http://via.placeholder.com/400x120/009955/fff?text=Complete"></td></tr>
<tr><td><table><tr><td> <em>Sub-Task 1: </em> Explain what you (the team) learned about the .service file configurations and related activities</td></tr>
<tr><td> <em>Response:</em> <div>service file configurations are a simple config file way to get the machine<br>to restart the consumers, without having to be restarted manually everytime, by telling<br>it to run a php file. I learned that the way I was<br>trying to make one service to start two consumers on the DB wasn't<br>working due to the types not permitting it. the solution was chosen to<br>make separate services to keep each consumer running. we learned the ways of<br>getting the [unit] and the service being made into systemctl and then how<br>to edit, reload, and start the service.&nbsp;through my research, I found the solution<br>to an issue we were having with the service failing due to not<br>having RemainAfterExit=yes tag in the service section.</div><br></td></tr>
<tr><td> <em>Sub-Task 2: </em> Highlight contributions</td></tr>
<tr><td> <em>Response:</em> <p>Smit and Dominic worked on researching the service files and making getting them<br>working on DB VM.<div>Jia worked on fixing nav bar on the mobile not<br>working, and the CSS</div><div><br></div><br></p><br></td></tr>
</table></td></tr>
<table><tr><td><em>Grading Link: </em><a rel="noreferrer noopener" href="https://learn.ethereallab.app/homework/IT490-451-M22/it490-systemd-services/grade/dwq2" target="_blank">Grading</a></td></tr></table>
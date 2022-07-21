<table><tr><td> <em>Assignment: </em> M8-Contributions</td></tr>
<tr><td> <em>Student: </em> Dominic Quitoni(dwq2)</td></tr>
<tr><td> <em>Generated: </em> 7/17/2022 7:24:50 PM</td></tr>
<tr><td> <em>Grading Link: </em> <a rel="noreferrer noopener" href="https://learn.ethereallab.app/homework/IT490-451-M22/m8-contributions/grade/dwq2" target="_blank">Grading</a></td></tr></table>
<table><tr><td> <em>Instructions: </em> <p>Each week I&#39;ll record your contributions to the class/group following the same outline.
You&#39;ll export this assignment to markdown (or download it as a markdown file) and add it to your group&#39;s Github.
You&#39;ll want to change the file name to include your ucid so it doesn&#39;t conflict with anyone else&#39;s files if it&#39;s in the same directory.</p>
<p>It&#39;s required to keep this branch in sync with all team members each module/week (you shouldn&#39;t cause anyone to lose their work).
 </p>
</td></tr></table>
<table><tr><td> <em>Deliverable 1: </em> Add screenshots of individual Issues (github recorded topics) that you worked on this week </td></tr><tr><td><em>Status: </em> <img width="100" height="20" src="http://via.placeholder.com/400x120/009955/fff?text=Complete"></td></tr>
<tr><td><table><tr><td> <em>Sub-Task 1: </em> Add a screenshot of each issue you worked on, include the link, and the status of the issue per the checklist</td></tr>
<tr><td><table><tr><td><img width="768px" src="https://user-images.githubusercontent.com/70656707/179424683-306554c8-8c2d-4097-bc25-64dbc2d93e60.png"/></td></tr>
<tr><td> <em>Caption:</em> <p>issue #58 <a href="https://github.com/MattToegel/IT490-M22-TBD1/issues/58">https://github.com/MattToegel/IT490-M22-TBD1/issues/58</a>, this is the script handling pushing the scripts, and files<br>needed for DB to a remote machine, with the parameters of the username,<br>IP_of_remote, and IP for the mq branch it is listening to<br></p>
</td></tr>
<tr><td><img width="768px" src="https://user-images.githubusercontent.com/70656707/179424694-41e0532b-c4a3-4d38-ab20-59f2ed5eabad.png"/></td></tr>
<tr><td> <em>Caption:</em> <p>issue #58 <a href="https://github.com/MattToegel/IT490-M22-TBD1/issues/58">https://github.com/MattToegel/IT490-M22-TBD1/issues/58</a>, this is the script handling pushing the scripts, and files<br>needed for App to a remote machine, with the parameters of the username,<br>IP_of_remote, and IP for the mq branch it is listening to<br></p>
</td></tr>
<tr><td><img width="768px" src="https://user-images.githubusercontent.com/70656707/179424828-04b46c07-71c7-415b-be8a-b32b6d3a9bfd.png"/></td></tr>
<tr><td> <em>Caption:</em> <p>this image is showing one of the configs for docker to init ~correctly<br></p>
</td></tr>
<tr><td><img width="768px" src="https://user-images.githubusercontent.com/70656707/179427786-43f6c6f1-25df-4a13-9135-d60cc4d3d183.png"/></td></tr>
<tr><td> <em>Caption:</em> <p><a href="https://github.com/MattToegel/IT490-M22-TBD1/issues/62">https://github.com/MattToegel/IT490-M22-TBD1/issues/62</a> this image shows my afilover script which can be run on my<br>instance-1 VM to listen to whatever machine and be able to detect it<br>going down and tell the other machines to change the IP from the<br>machine that went down to <br></p>
</td></tr>
<tr><td><img width="768px" src="https://user-images.githubusercontent.com/70656707/179427790-969f543b-cdd0-4c4e-a465-3a8d16581820.png"/></td></tr>
<tr><td> <em>Caption:</em> <p>this image is in the same issue as the prior, but this script<br>is handling changing the IP inside of the live branch for the rsyslog,<br>rabbitmq, and db configs[only for db server]<br></p>
</td></tr>
<tr><td><img width="768px" src="https://user-images.githubusercontent.com/70656707/179428701-cb7e8d6f-7ec2-4079-a6b3-ac5badbcaa3e.png"/></td></tr>
<tr><td> <em>Caption:</em> <p>here is just a image of the instance group that I had spent<br>time on trying to get to work to be able to utilize the<br>GCP load balancer. this way requires the creation of an image from the<br>disk of, in this case my rabbitMq-QA build, then making that image into<br>a instance template, and then into the instance group which can auto scale<br>based on CPU utilization, and have health checkup to see if it is<br>up. this also required adding portforwarding for all of rabbitmqs ports and ports<br>for the healthcheck to get through<br></p>
</td></tr>
</table></td></tr>
</table></td></tr>
<table><tr><td> <em>Deliverable 2: </em> Discuss </td></tr><tr><td><em>Status: </em> <img width="100" height="20" src="http://via.placeholder.com/400x120/009955/fff?text=Complete"></td></tr>
<tr><td><table><tr><td> <em>Sub-Task 1: </em> Briefly talk about what you contributed to the above issues/tracking items</td></tr>
<tr><td> <em>Response:</em> <p>I worked on researching and attempting to implement docker containerization on our MQ<br>and app VMs in a hope of increasing the efficiency of deploying updates<br>to our QA and Production branches. this method was produced after work on<br>my base script which is intended to migrate the required files for the<br>individual VMs so that a blank VM would be brought up to speed<br>and work to have a working theory.&nbsp;<br></p><br></td></tr>
<tr><td> <em>Sub-Task 2: </em> Any problems this week with your tasks or any group members? If so, how did you resolve them or how do you plan to resolve them.</td></tr>
<tr><td> <em>Response:</em> <p>falling into the rabbit hole of configs of docker, and Kubernetes, while being<br>sick. API hasn&#39;t been fully implemented or had much help with implementing any<br>load balancing. receiving little to no contact or collab on researching or implementation<br>methods till Sunday, which was deciding to have an emergency meeting tomorrow to<br>fix and finalize<br></p><br></td></tr>
</table></td></tr>
<table><tr><td><em>Grading Link: </em><a rel="noreferrer noopener" href="https://learn.ethereallab.app/homework/IT490-451-M22/m8-contributions/grade/dwq2" target="_blank">Grading</a></td></tr></table>
<table><tr><td> <em>Assignment: </em> IT490 - W2 - Group Remote Example</td></tr>
<tr><td> <em>Student: </em> Smit Joshi(saj39)</td></tr>
<tr><td> <em>Generated: </em> 6/4/2022 12:08:48 PM</td></tr>
<tr><td> <em>Grading Link: </em> <a rel="noreferrer noopener" href="https://learn.ethereallab.app/homework/IT490-450-M22/it490-w2-group-remote-example/grade/saj39" target="_blank">Grading</a></td></tr></table>
<table><tr><td> <em>Instructions: </em> <div><div>Note: Only one person needs to generate this on the Learn platform and only one person needs to submit the link to canvas.</div><div>Work on this as a team.</div><ol><li>Create a new branch following the desired branch name</li><li>Create 2 VMs under your chosen server provider(s) (should be separate students)</li><li>Get the example code working remotely and capture evidence of it working</li><li>Fill in the below deliverables</li><li>Label the submission file as W2-Example-Group.md</li><li>Create a pull request for this branch and merge the code to the primary branch</li><li>From this hw branch navigate to this md file and submit the direct link to canvas.</li><li>You may want to turn off your server so you don't waste any quota</li></ol></div><div><br></div></td></tr></table>
<table><tr><td> <em>Deliverable 1: </em> Analysis </td></tr><tr><td><em>Status: </em> <img width="100" height="20" src="http://via.placeholder.com/400x120/009955/fff?text=Complete"></td></tr>
<tr><td><table><tr><td> <em>Sub-Task 1: </em> What changes were necessary to get the example to work across VMs?</td></tr>
<tr><td> <em>Response:</em> <p>The configuration file had to be changed to set the host, user, and<br>password to the remote VMs IP. A new user had to be created<br>as rabbitmq does not allow the guest user to connect remotely. The firewall<br>on the remote VM also had to be configured to allow connections to<br>port 5672.<br></p><br></td></tr>
<tr><td> <em>Sub-Task 2: </em> List each group member and mention how they contributed to this assignment (each member must be mentioned)</td></tr>
<tr><td> <em>Response:</em> <ul><br><li>Smit: Worked with Jia to configure VM to remotely send messages. Took<br>turns connecting and sending messages to our rabbitmq servers respectively.<div>- JiaZhong: Worked with<br>Smit to configure VM to remotely send messages. Took turns connecting and sending<br>messages to our rabbitmq servers respectively.</div><div>- Emily: Did not need for this assignment</div><div>-<br>Javier: Did not need for this assignment</div><div>- Dominic: Did not need for this<br>assignment</div><br></li><br></ul><br></td></tr>
</table></td></tr>
<table><tr><td> <em>Deliverable 2: </em> Example Evidence </td></tr><tr><td><em>Status: </em> <img width="100" height="20" src="http://via.placeholder.com/400x120/009955/fff?text=Complete"></td></tr>
<tr><td><table><tr><td> <em>Sub-Task 1: </em> Screenshot of the sample request being published and receiving a reply</td></tr>
<tr><td><table><tr><td><img width="768px" src="https://user-images.githubusercontent.com/67155481/172013919-d84817db-d828-4479-9982-cde39bf547aa.png"/></td></tr>
<tr><td> <em>Caption:</em> <p>Jia&#39;s VM as Client (Send message)<br></p>
</td></tr>
<tr><td><img width="768px" src="https://user-images.githubusercontent.com/67155481/172014011-7c52b587-9bfa-47a1-b4f8-3be1e6b9a24d.png"/></td></tr>
<tr><td> <em>Caption:</em> <p>Smit&#39;s VM as Client (Send message)<br></p>
</td></tr>
</table></td></tr>
<tr><td> <em>Sub-Task 2: </em> Screenshot of the consumer receiving the request and replying back</td></tr>
<tr><td><table><tr><td><img width="768px" src="https://user-images.githubusercontent.com/67155481/172013888-92fac2bb-bbfb-44d3-ae9d-7fdfaae64ef6.png"/></td></tr>
<tr><td> <em>Caption:</em> <p>Smit&#39;s VM as Server (Receive message)<br></p>
</td></tr>
<tr><td><img width="768px" src="https://user-images.githubusercontent.com/67155481/172013985-ad01dfcb-b8dc-4536-bcfb-7123f32be79f.png"/></td></tr>
<tr><td> <em>Caption:</em> <p>Jia&#39;s VM as Server (Receive message)<br></p>
</td></tr>
</table></td></tr>
</table></td></tr>
<table><tr><td> <em>Deliverable 3: </em> Discussion </td></tr><tr><td><em>Status: </em> <img width="100" height="20" src="http://via.placeholder.com/400x120/009955/fff?text=Complete"></td></tr>
<tr><td><table><tr><td> <em>Sub-Task 1: </em> What issues did you face and how did you  as a team resolve them?</td></tr>
<tr><td> <em>Response:</em> <p>The first issue we had was trying to use the guest user to<br>remotely access rabbitmq which is not allowed. We created a new user and<br>set permissions which solved that problem.<div>Another issue was setting the firewall rules to<br>allow inbound traffic on the correct port for rabbitmq. We set up firewall<br>rules to allow traffic from all IP addresses on port 5672, which solved<br>our issue.</div><br></p><br></td></tr>
</table></td></tr>
<table><tr><td><em>Grading Link: </em><a rel="noreferrer noopener" href="https://learn.ethereallab.app/homework/IT490-450-M22/it490-w2-group-remote-example/grade/saj39" target="_blank">Grading</a></td></tr></table>
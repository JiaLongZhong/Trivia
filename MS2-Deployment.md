<table><tr><td> <em>Assignment: </em> IT490 - Milestone 2 - Deployment Code Promotion System</td></tr>
<tr><td> <em>Student: </em> Emily Hontiveros(ebh4)</td></tr>
<tr><td> <em>Generated: </em> 7/8/2022 11:41:45 PM</td></tr>
<tr><td> <em>Grading Link: </em> <a rel="noreferrer noopener" href="https://learn.ethereallab.app/homework/IT490-450-M22/it490-milestone-2-deployment-code-promotion-system/grade/ebh4" target="_blank">Grading</a></td></tr></table>
<table><tr><td> <em>Instructions: </em> <ol><li>Create a new branch per the Desired Branch Name below</li><li>For this milestone, as a team you'll be developing a custom deployment system via bash scripts<br></li><li>You will not be able to use GitHub for migrating files between DEV -&gt; QA -&gt; Prod</li><li>If you haven't done so yet, create 4 new VMs replicating the installtion of your DEV lane but mark/label these as QA</li><ol><li>You may preemtively do the same for Prod, but it's not required for this deliverable</li></ol><li>You will implement a system that allows transferring of files from VM A to VM B<br></li><ol><li>Let me know if it'll be a push (A -&gt; B), a pull system (B &lt;- A), or both</li></ol><li>Received files should go to a landing point directory, not directly to the live data</li><li>Any live files should be backed up into a backup directory (may want to include the timestamp in the backup name)</li><li>Once live files are backed up, overwrite/replace those files with the recently received changes</li><li>After this process, the new files should be usable/working on VM B</li><ol><li>Note: You may need/want to stop services during this process and start them upon completion</li></ol><li>File/Data Support</li><ol><li>Server-side code like PHP files</li><li>Config files</li><li>Any content</li><li>DB structural changes (preferrably via record SQL files)</li><li>Ensure you don't copy dev data/content to QA/Prod, each lane may need its own config data in some cases</li></ol><li>The system should support reverting changes / restoration of backups</li><ol><li>Create a new backup of the current/original file</li><li>Restore the previously backed up files from a specific "transaction" (note the timestamp comment above)</li><li>After the process, the application should be in the state of the backup files (a previous version of whatever was restored)</li></ol><li>Create a deployment_system.md file and fill in the content from this deliverable</li><li>Add/Commit/Push to the Desired Branch Name</li><li>Submit the direct link to the .md file from this branch to Canvas</li></ol><div>Grades will be based on how well it works and meets the criteria.&nbsp;</div><div>Points will be deducted for missing sections or not following instructions.<br></div></td></tr></table>
<table><tr><td> <em>Deliverable 1: </em> Promoting New Files </td></tr><tr><td><em>Status: </em> <img width="100" height="20" src="http://via.placeholder.com/400x120/009955/fff?text=Complete"></td></tr>
<tr><td><table><tr><td> <em>Sub-Task 1: </em> Screenshots of the process between VM A and VM B</td></tr>
<tr><td><table><tr><td><img width="768px" src="https://user-images.githubusercontent.com/72458226/178088834-589b225f-43ee-4806-976f-5ab89a31095c.png"/></td></tr>
<tr><td> <em>Caption:</em> <p>Deliverable #1 - Before dropoff location <br></p>
</td></tr>
<tr><td><img width="768px" src="https://user-images.githubusercontent.com/72458226/178088867-dafc4c60-8370-49b9-a1f0-91667848fccb.png"/></td></tr>
<tr><td> <em>Caption:</em> <p>Deliverable #1 After drop off location<br></p>
</td></tr>
</table></td></tr>
</table></td></tr>
<table><tr><td> <em>Deliverable 2: </em> Promoting Updates </td></tr><tr><td><em>Status: </em> <img width="100" height="20" src="http://via.placeholder.com/400x120/009955/fff?text=Complete"></td></tr>
<tr><td><table><tr><td> <em>Sub-Task 1: </em> Screenshots of the process between VM A and VM B</td></tr>
<tr><td><table><tr><td><img width="768px" src="https://user-images.githubusercontent.com/72458226/178088933-dbb68f3f-5328-4cf5-93ba-3d2bbca168a4.png"/></td></tr>
<tr><td> <em>Caption:</em> <p>Deliverable #2 before - backup location<br></p>
</td></tr>
<tr><td><img width="768px" src="https://user-images.githubusercontent.com/72458226/178088951-b5bce6cb-ad04-41f5-b698-b0880d0ad542.png"/></td></tr>
<tr><td> <em>Caption:</em> <p>Deliverable #2 after - backup location<br></p>
</td></tr>
</table></td></tr>
</table></td></tr>
<table><tr><td> <em>Deliverable 3: </em> Restoring previous versions </td></tr><tr><td><em>Status: </em> <img width="100" height="20" src="http://via.placeholder.com/400x120/009955/fff?text=Complete"></td></tr>
<tr><td><table><tr><td> <em>Sub-Task 1: </em> Screenshots of file restoration</td></tr>
<tr><td><table><tr><td><img width="768px" src="https://user-images.githubusercontent.com/72458226/178089008-6a63c0bf-ec49-4bce-a636-b680d90dd3e8.png"/></td></tr>
<tr><td> <em>Caption:</em> <p>Deliverable #3 this shows lib being staged in the dropoff folder, showing the<br>REAME.md file content that will be changed in the updated code.  then<br>its implemented into live.<br></p>
</td></tr>
<tr><td><img width="768px" src="https://user-images.githubusercontent.com/72458226/178089017-b5bd0097-3479-4a78-aaf3-242bf0be8cc3.png"/></td></tr>
<tr><td> <em>Caption:</em> <p>Derivable #3 shows the ls of lib being live with the edited README<br>and the REAME in the backup location, right before running my do_backupRestore.sh script<br>that grabs the last backup file written in and copies it back to<br>the live directory. and the README file has been changed back to the<br>original version<br></p>
</td></tr>
</table></td></tr>
</table></td></tr>
<table><tr><td> <em>Deliverable 4: </em> Deployment/Promotion System </td></tr><tr><td><em>Status: </em> <img width="100" height="20" src="http://via.placeholder.com/400x120/009955/fff?text=Complete"></td></tr>
<tr><td><table><tr><td> <em>Sub-Task 1: </em> Screenshots of scripts</td></tr>
<tr><td><table><tr><td><img width="768px" src="https://user-images.githubusercontent.com/72458226/178089061-c1305793-d665-432a-869f-964c31dacfb5.png"/></td></tr>
<tr><td> <em>Caption:</em> <p>Deliverable #4 - script used for Deliverable #1 and Deliverable #2, from VM<br>A to VM B<br></p>
</td></tr>
<tr><td><img width="768px" src="https://user-images.githubusercontent.com/72458226/178089070-d8ecdcfa-8f0f-4094-857d-771d6725d0d6.png"/></td></tr>
<tr><td> <em>Caption:</em> <p>Deliverable #4 - scripts used for Deliverable #1 and Deliverable #2,  on<br>VM B<br></p>
</td></tr>
</table></td></tr>
<tr><td> <em>Sub-Task 2: </em> Walk through the process</td></tr>
<tr><td> <em>Response:</em> <p>The scripts used for deployment for Deliverable #1 and #2 included push.sh and<br>migrate.sh, from VM A to VM B. Push.sh was able to hardcode the<br>key used for the communications exposing the source path, user, host, and destination<br>path. Migrate.sh is used to get source and destination, given a static host<br>and user, which we have. It can tell the user what&#39;s going on<br>with the echo commands, and ultimately allow to ssh to another machine.<div><br><div>The scripts<br>used for deployment for Deliverable #1 and #2 included do_migrate.sh and implement.sh on<br>VM B. Do_migrate.sh sets up the dropoff and live directory, which we created<br>under Jia&#39;s VM. Implement.sh is the migration system to loop over every file<br>in the source.&nbsp;</div></div><br></p><br></td></tr>
</table></td></tr>
<table><tr><td> <em>Deliverable 5: </em> Contributions </td></tr><tr><td><em>Status: </em> <img width="100" height="20" src="http://via.placeholder.com/400x120/009955/fff?text=Complete"></td></tr>
<tr><td><table><tr><td> <em>Sub-Task 1: </em> Team member contribution</td></tr>
<tr><td> <em>Response:</em> <p>Javier Artiga - worked on the scripts on both VM(a) and VM(b) with<br>Jia, Dominic and Emily. Made sure that VM(b) authorized file in .ssh had<br>the proper ssh key from VM(a). Tested and troubleshooted that the file from<br>VM(a) was sent to VM(b) and then moved to proper directory as needed.<div><br></div><div>Dominic<br>Quitoni - worked on scripting and getting migration to work with new qa<br>build VMs. Worked more specifically on making a script to restore last backup<br>made. This was done through substrings the date off the end of the<br>backup element, to reinstate it to the live path. Send in deliverable #3.<br>Also started work on a base.sh script to automatically install the needed library<br>dynamically based on args based into the script. It is mainly built out<br>to fully rebuild the rabbitmq service with users to give functionality to the<br>respective VMs, app api and DB.<br></div><div><br></div><div>Jia Zhong - worked on the scripts on<br>both VM(a) and VM(b) with Javier, Dominic, and Emily. Create APP-dev and troubleshoot/test<br>file tranfering proccess from VM(a) to VM(b). Wrote the shell files for the<br>team(push.sh,implement.sh, do_migrate.sh ,migrate_sample.sh. crudeupgrade.sh, and crudebatchupgrade.sh.<br></div><div><br></div><div>Smit Joshi -&nbsp;worked on the issues on the<br>scripts.&nbsp;</div><div><br></div><div>Emily Hontiveros - worked on the scripts on both VM(a) and VM(b) with<br>Jia, Dominic and Javier. Helped make sure that VM(b) authorized file in .ssh<br>had the proper ssh key from VM(a). Wrote the shell files for the<br>team on Github (push.sh,implement.sh, do_migrate.sh ,migrate_sample.sh. crudeupgrade.sh, and crudebatchupgrade.sh.).</div><br></p><br></td></tr>
<tr><td> <em>Sub-Task 2: </em> As a team</td></tr>
<tr><td> <em>Response:</em> <p>Initially, it was intimidating that we could lose our VM during this process,<br>but Dominic was experienced in making sure that the implement.sh can&#39;t do that.<br>When we briefly started working on bash scripting, it was difficult to be<br>successful, but was able to do our own research to get a proper<br>start. As a group there were some other difficulties including basename in implement.sh<br>with minor typo&#39;s confusing $1 and&nbsp;$l with each other.&nbsp;<br></p><br></td></tr>
</table></td></tr>
<table><tr><td><em>Grading Link: </em><a rel="noreferrer noopener" href="https://learn.ethereallab.app/homework/IT490-450-M22/it490-milestone-2-deployment-code-promotion-system/grade/ebh4" target="_blank">Grading</a></td></tr></table>
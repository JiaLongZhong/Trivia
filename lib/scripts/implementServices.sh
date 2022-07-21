
sudo cp -r /home/$USER/dropoff/services /etc/systemd/system/
for file in /etc/systemd/system/services/*.service; do
    sudo systemctl start $file
    sudo systemctl enable $file
done
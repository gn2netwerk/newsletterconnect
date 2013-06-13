# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

  config.vm.box = "gn2"
  config.vm.box_url = "http://www.gn2-netwerk.de/download/gn2.box"
  
  config.vm.hostname = "oxidbox";
    
  config.vm.network :forwarded_port, host: 7777, guest: 80
  
  $dirname = "/home/vagrant/oxarchive/modules/" << File.basename(Dir.getwd)
  config.vm.synced_folder ".", $dirname, :owner => "www-data", :group => "www-data", :create => true

end
#!/bin/bash
export LC_ALL="en_US.UTF-8"

echo "update start......" 
date

echo "start update ....."
#svn revert --depth=infinity /home/tuwen/backend/
svn up $1


echo "update end......" 

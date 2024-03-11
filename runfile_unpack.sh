#!/bin/bash
echo "Ready to unpack codeadv files. Cancel by quitting now."
read -n 1 -s -r -p "Press any key to continue"
tar -zxvf runfile.tar.gz
echo "All done."
read -n 1 -s -r -p "Press any key to continue"
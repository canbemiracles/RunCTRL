#!/bin/bash

cd /opt/frontend
rm -rf ./dist
mkdir ./dist
cp index.html ./dist
cp robots.txt ./dist
npm install
npm run build
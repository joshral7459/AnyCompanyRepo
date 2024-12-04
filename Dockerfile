# Use the official NGINX base image
FROM 841162696521.dkr.ecr.us-east-1.amazonaws.com/nginx:alpine
# Variables defined in buildspec.yml
COPY ./html /usr/share/nginx/html
# Expose port 80 for the web server
EXPOSE 80
# Start NGINX server
CMD ["nginx", "-g", "daemon off;"]

# Use the official NGINX base image
FROM ${AWS_ACCOUNT_ID}.dkr.ecr.${AWS_DEFAULT_REGION}.amazonaws.com/nginx:alpine
# Copy custom configuration file to the NGINX configuration directory (optional)
# COPY ./nginx.conf /etc/nginx/nginx.conf
# Copy your website's static files to the NGINX document root directory
COPY ./html /usr/share/nginx/html
# Expose port 80 for the web server
EXPOSE 80
# Start NGINX server
CMD ["nginx", "-g", "daemon off;"]

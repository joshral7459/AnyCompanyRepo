# Use the official NGINX base image
FROM ${AWS_ACCOUNT_ID}.dkr.ecr.${AWS_DEFAULT_REGION}.amazonaws.com/nginx:alpine
# Variables defined in buildspec.yml
COPY ./html /usr/share/nginx/html
# Expose port 80 for the web server
EXPOSE 80
# Start NGINX server
CMD ["nginx", "-g", "daemon off;"]

# Use Node.js version 18 as the base image
FROM node:18

# Set the working directory to /frontend in the container
WORKDIR /frontend

# Copy all files from the local 'frontend' directory to the container's /frontend directory
COPY ./frontend /frontend

# Install the npm dependencies
RUN npm install

# Install the 'serve' package globally to serve the built files
RUN npm i -g serve

# Build the application for production
RUN npm run build

# Expose port 3000 to allow external access
EXPOSE 3000

# Command to serve the built application from the 'dist' directory
CMD [ "serve", "-s", "dist" ]

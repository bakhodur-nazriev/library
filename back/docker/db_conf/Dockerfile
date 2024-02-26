# Use the official PostgreSQL image as the base image
FROM postgres:15-alpine

# Set environment variables for creating the database
ENV POSTGRES_DB library_db
ENV POSTGRES_USER postgres
ENV POSTGRES_PASSWORD postgres

# Run SQL script to create the database
COPY ./init.sql /docker-entrypoint-initdb.d/

# Expose PostgreSQL port
EXPOSE 5432

COPY ./install-extensions.sql /docker-entrypoint-initdb.d

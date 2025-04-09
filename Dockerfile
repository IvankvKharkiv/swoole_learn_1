FROM phpswoole/swoole

# Copy the application files
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Expose the port the app runs on
EXPOSE 9501

RUN apt-get update \
  && DEBIAN_FRONTEND=noninteractive apt-get install -y \
    net-tools \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/*

CMD ["php", "index.php"]

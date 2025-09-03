const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
  entry: {
    'typeform-embed-library': './src/typeform-library.js',
    'typeform-embed': './src/typeform-embed.js',
    'typeform-embed-simple': './src/typeform-embed-simple.js'
  },
  output: {
    path: path.resolve(__dirname, 'assets/js'),
    filename: '[name].min.js'
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env']
          }
        }
      },
      {
        test: /\.css$/,
        use: [MiniCssExtractPlugin.loader, 'css-loader']
      }
    ]
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: '../css/[name].css'
    })
  ],
  externals: {
    jquery: 'jQuery'
  }
};
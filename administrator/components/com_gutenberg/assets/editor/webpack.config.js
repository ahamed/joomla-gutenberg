const path = require('path');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

module.exports = {
    entry: {
        editor: './src/index.js',
        preview: './src/main.js'
    },
    output: {
        path: path.resolve(__dirname, 'dist'),
        filename: 'js/[name].bundle.js',
        publicPath: './dist'
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /(node_modules)/,
                use: { loader: 'babel-loader' }
            },
            {
                test: /\.(s?css)$/,
                use: [
                    'style-loader',
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    'sass-loader'
                ],
            },
            {
                test: /\.(png|jpe?g|gif|svg)$/i,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            outputPath: 'images/',
                            name: "[name].[ext]"
                        }
                    }
                ]
                
            },
            {
                test: /\.(ttf|woff)$/i,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            outputPath: 'fonts/',
                            name: "[name].[ext]"
                        }
                    }
                ]
            }
        ]
    },
    plugins: [
        new MiniCssExtractPlugin(
            {
                filename: "./css/[name].bundle.css",
            }
        ),
    ],
    devtool: "source-map"
};
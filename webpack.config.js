const path = require('path')

module.exports = {
    entry: {
        'study': './assets/js/study.js',
        'admin/lesson': './assets/js/admin/lesson.js',
    },
    output: {
        filename: '[name].js',
        path: path.resolve(__dirname, 'public/assets/js'),
        libraryTarget: 'var',
        library: 'Study'
    },
    watch: true,
    externals: {
        'vue': 'Vue'
    }
}

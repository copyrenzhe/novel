'use strict';

const NODE_ENV = process.env.NODE_ENV || 'local';

module.exports = {
    /**
     * 服务环境配置
     */
    apps: [
        {
            // 服务别名
            name: 'novel',
            script: 'app.js',
            node_args: '--harmony',
            // 以下是日志输出选项
            log_file: 'storage/logs/node-combined.log',
            out_file: 'storage/logs/node-out.log',
            error_file: 'storage/logs/node-err.log',
            merge_logs: true,
            log_date_format : 'YYYY-MM-DD HH:mm:ss',
            // 以下是站点配置
            env: {
                NODE_SITE: 'http://www.shu000.com/index.php',
                NODE_ENV: NODE_ENV, // 当前Node服务环境
                port: 10500 // 服务端口
            }
        }
    ]
};
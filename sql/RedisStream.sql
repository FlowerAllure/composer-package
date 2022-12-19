-- 删除整个Stream
del my_stream

-- 获取范围的消息，
-- -代表最小ID, +代表最大ID, count 代表数量
xrange my_stream - +
xrange my_stream - + count 2

-- 查看消息长度
xlen my_stream

-- 删除消息
xdel my_stream 0-1

-- 获取消息
-- count 获取条数 0 从0开始读取 $最后一个ID读取 block 阻塞获取
xread count 2 streams my_stream 0
xread count 1 block 0 streams my_stream 0

-- 查看消费组列表
xinfo groups  my_stream

-- 添加消费组 ID为0表示从头开始消费，为$表示只消费新的消息，也可以自己指定
xgroup create my_stream head_group 0
xgroup create my_stream middle_group 1-6
xgroup create my_stream tail_group $


-- 消费消费组消息
-- c1 消费者 count 消费消息数量 block 最大等待时间 0 一直等待
xreadgroup group middle_group c1 count 1 streams my_stream >
xreadgroup group middle_group c2 count 1 streams my_stream >
xreadgroup group middle_group a1 count 1 block 0 streams my_stream >

-- 查看消费者列表
xinfo consumers my_stream middle_group

-- 确认消息
xack my_stream middle_group 1-8
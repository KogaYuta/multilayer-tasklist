// tasks→dataに変換するプログラムです
// main-rec2.jsの宣言前
// 使用するmain-rec2.jsの変数は以下の通りです
// data

let tasks_debug = [
    {
        id:0,
        parent_id:0,
        project_flag:1,
        content:"A"
    },    
    {
        id:1,
        parent_id:0,
        project_flag:0,
        content:"B"
    }, 
    {
        id:2,
        parent_id:0,
        project_flag:0,
        content:"C"
    }, 
    {
        id:3,
        parent_id:2,
        project_flag:0,
        content:"D"
    }, 
    {
        id:4,
        parent_id:2,
        project_flag:0,
        content:"E"
    }, 
    {
        id:5,
        parent_id:2,
        project_flag:0,
        content:"F"
    }, 
    {
        id:6,
        parent_id:0,
        project_flag:0,
        content:"G"
    }, 
    {
        id:7,
        parent_id:0,
        project_flag:0,
        content:"H"
    }, 
    {
        id:8,
        parent_id:7,
        project_flag:0,
        content:"I"
    }, 
    {
        id:9,
        parent_id:7,
        project_flag:0,
        content:"J"
    }, 
    {
        id:10,
        parent_id:0,
        project_flag:0,
        content:"K"
    }, 
    {
        id:11,
        parent_id:9,
        project_flag:0,
        content: "L"
    },
    {
        id:12,
        parent_id:11,
        project_flag:0,
        content: "M"
    },
];

const createParentIdList = (tasks) => {
    
    let parentIdList = []; //親タスクのidを追加する
    // parentIdList作成
    for(let i=0;i<tasks.length;i++) {
        let temp = tasks[i];
        //現在のタスクのparent_idがparentIdListに存在しない場合
        if ( parentIdList.indexOf(temp.parent_id) == -1 ) {
            parentIdList.push(temp.parent_id);
        }    
    }
    
    return parentIdList;
};

const createDataTree = (tasks, parentIdList) => {
    let dataTree = {};
    for(let i=0;i<parentIdList.length;i++) {
        dataTree[parentIdList[i]]=[];
    }
    
    // dataTreeのchildren_idを代入する
    for(let i=0;i<tasks.length;i++) {
        if (!(tasks[i].project_flag==1)) {
            dataTree[tasks[i].parent_id].push(tasks[i].id);
        }
    }
    
    return dataTree;
};

const getTaskById = (tasks, id) => {
    let n = tasks.length;
    for (let i=0; i<n; i++) {
        if (tasks[i].id == id) {
            return tasks[i];
        }
    }
};

const convertIdtoTask = (tasks, id) => {
    for(let i=0;i<tasks.length;i++) {
        if (tasks[i].id == id) {
            return tasks[i];
        }
    }
};

const calcLayer = (tasks, id) => {
    let layer = 1;
    let flag = true;
    let projectId = -1;
    for(let i=0;i<tasks.length;i++){
        if(tasks[i].project_flag==1) {
            projectId = tasks[i].id;
        }
    }
    
    let currentTasks = getTaskById(tasks, id);
    
    if (currentTasks.parent_id == projectId && currentTasks.id == projectId) {
        return 0;
    } else {
        while (flag) {
          if (currentTasks.parent_id != projectId) {
              let tempId = currentTasks.parent_id;
              currentTasks = getTaskById(tasks, tempId);
              layer++;
          } else {
              flag = false;
              return layer;
          }
        }
    }
};


const createDepthObject = (tasks) => {
    let depthObject = {};
    for(let i=0; i<tasks.length; i++) {
        let id = tasks[i].id;
        let depth = calcLayer(tasks, id);
        if (depth in depthObject) {
            depthObject[depth].push(id);
        } else {
            depthObject[depth] = [id];
        }
    }
    
    return depthObject;
};

const getPathId = (tasks,dataTree, id) => {
    let ind = [id];
    let flag = true;
    let projectId = -1;
    for(let i=0;i<tasks.length;i++){
        if(tasks[i].project_flag==1) {
            projectId = tasks[i].id;
        }
    }
    
    let currentTasks = getTaskById(tasks, id);
    
    console.log("currentTasks", currentTasks);
    if (currentTasks.parent_id == projectId && currentTasks.id == projectId) {
        return 0;
    } else {
        while (flag) {
            // projectノード直下のtaskノードでない場合, 3層以降
            if (currentTasks.parent_id != projectId) {
                currentTasks = getTaskById(tasks, currentTasks.parent_id);
                ind.unshift(currentTasks.id);
            // projectノード直下のtaskノードである場合, 2層目
            } else {
                flag = false;
                ind.unshift(projectId);
                return ind;
            }
            
        }
    }
    
};

const getPathInd = (tasks, dataTree, depthObject, id) => {
    // depth: depthObject
    let ind = [0];
    let pathId = getPathId(tasks, dataTree, id);
    let n = pathId.length;
    
    // 階層方向にloop
    // 2階層目以降
    for(let i=1;i<n;i++) {
        let m = depthObject[i].length;
        let counter = 0;
        let prevParentId = -1; // 次のif文で必ずfalseにするため
        
        for(let j=0;j<m;j++) {
            
            let tempId = depthObject[i][j];
            let tempParentId = convertIdtoTask(tasks, tempId).parent_id;
            
            if (prevParentId == tempParentId) {
                counter++;
            } else {
                counter = 0;
            }
            
            if (depthObject[i][j] == pathId[i]) {
                ind.push(counter);
            }
            
            prevParentId = tempParentId;
        }
    }
    
    return ind;
}


const getProjectTaskId = (tasks) => {
    let n = tasks.length;
    for (let i=0; i<n; i++) {
        if (tasks[i].project_flag==1) {
            return tasks[i].id;
        }
    }
};

const createData = (tasks) => {
    let data = {};
    
    const parentIdList = createParentIdList(tasks);
    const dataTree = createDataTree(tasks, parentIdList);
    const depthObject = createDepthObject(tasks);
    
    let n = Object.keys(depthObject).length;
    
    //階層の深さ方向にループ
    for (let i=0; i<n; i++) {
        let m = depthObject[i].length;
        
        // 階層の水平方向にループ
        for (let j=0; j<m; j++) {
            
            let temp = {};
            
            // i=0はprojectしかないので追加
            if (i == 0) {
                let id = getProjectTaskId(tasks);
                temp.name=getTaskById(tasks, id).content, //idから取得する関数に変える
                temp.children=[];
                
                data = temp;
                
            // i=1以降、つまり2階層目以降
            } else {
                let id = depthObject[i][j];
                
                // jは現在操作するタスクのid
                if (parentIdList.indexOf(id) != -1) {
                    temp.name = getTaskById(tasks, id).content; //idから取得する関数に変える
                    temp.children = [];
                } else {
                    temp.name = getTaskById(tasks, id).content; //idから取得する関数に変える
                }
                
                let ind = getPathInd(tasks, dataTree, depthObject, id);
                let l = ind.length -1;
                
                let currentData = data;
                for (let k=0; k<l; k++) {
                    if (k == 0) {
                        currentData = currentData.children;
                    } else {
                        currentData = currentData[ind[k]].children;
                    }
                }
                
                currentData.push(temp);
            }
            
        }
    }
    
    return data;
};





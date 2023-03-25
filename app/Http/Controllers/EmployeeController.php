<?php

namespace App\Http\Controllers;
use Validator;
use App\Http\Resources\EmployeeResource;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Http\Requests\EmployeeCreatRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use Spatie\QueryBuilder\QueryBuilder;
use OpenApi\Annotations as OA;


/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Employee microservice Api Documentation",
 *     description="Employee microservice Api Documentation",
 *     @OA\Contact(
 *         name="Artush PEetrosyan",
 *         email="apyan@gmail.com"
 *     ),
 * ),
 * @OA\Server(
 *     url="/api/v1",
 * ),
 */

class EmployeeController extends Controller
{

/**
 * @SWG\Post(
 *     path="/api/employee",
 *     summary="Create a new Employee",
 *     tags={"Employee"},
 *     @SWG\Parameter(name="name", type="string", in="formData", description="Employee's name", required=true),
 *     @SWG\Parameter(name="surname", type="string", in="formData", description="Employee's surname address", required=true),
 *     @SWG\Parameter(name="patronymic", type="string", in="formData", description="Employee's patronymic", required=true),
 *     @SWG\Parameter(name="birthday", type="date", in="formData", description="Employee's birthday", required=true),
 *     @SWG\Parameter(name="phone", type="string", in="formData", description="Employee's phone", required=true),
 *     @SWG\Parameter(name="position", type="enum", in="formData",values="['Супервайзер', 'Админ',]", description="Employee's  position", required=true),
 *     @SWG\Response(response="201", description="User created successfully"),
 *     @SWG\Response(response="422", description="Validation error")
 * )
 */
    public function create(EmployeeCreatRequest $request){
        $validated = $request->validated();
        $employee = Employee::create($validated);

        return [
              'message'=>'Added Employee',
              'data' => $employee,
        ];

    }

/**
 * @OA\Post(
 *     path="/api/employee",
 *     summary="Update employee",
 *     tags={"Employee"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="John Smith"),
 *             @OA\Property(property="surname", type="string", format="string", example="Smith"),
 *             @OA\Property(property="patronymic", type="string", format="string", example="Smiths"),
 *             @OA\Property(property="birthday", type="date", example="15.02.1995"),
 *             @OA\Property(property="position", type="date", example="Админ"),
 *             @OA\Property(property="phone", type="string", example="+79260000000"),
 *         )
 *     ),
 *     @OA\Response(
 *         response="201",
 *         description="Employee updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example="1"),
 *             @OA\Property(property="name", type="string", example="John Smith"),
 *             @OA\Property(property="surname", type="string", format="string", example="Smith"),
 *             @OA\Property(property="patronymic", type="string", format="string", example="Smiths"),
 *             @OA\Property(property="birthday", type="date", example="15.02.1995"),
 *             @OA\Property(property="position", type="date", example="Админ"),
 *             @OA\Property(property="phone", type="string", example="+79260000000"),
 *         )
 *     ),
 *     @OA\Response(response="422", description="Validation error")
 * )
 */
    public function edit(EmployeeUpdateRequest $request,$id){

        $validated = $request->validated();

        $employee = Employee::where('id',$id)->update($validated);

         return [
            'message'=>'Updated Employee',
      ];
    }
/**
 * @OA\Get(
 *     path="/api/employees/{id}",
 *     summary="Retrieve an employee by ID",
 *     tags={"Employees"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the employee to retrieve",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Employee record retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="name",
 *                 type="string",
 *                 example=" jon"
 *             ),
 *              @OA\Property(
 *                 property="surname",
 *                 type="string",
 *                 example=" smith"
 *             ),
 *                @OA\Property(
 *                 property="patronymic",
 *                 type="string",
 *                 example=" jons"
 *             ),
 *              @OA\Property(
 *                 property="birthday",
 *                 type="date",
 *                 example=" 15.02.2020"
 *             ),
 *              @OA\Property(
 *                 property="position",
 *                 type="enum",
 *                 example=" Админ"
 *             ),
 *              @OA\Property(
 *                 property="phone",
 *                 type="string",
 *                 example=" +792665854541"
 *             ),
 *         )
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="Employee not found"
 *     )
 * )
 */
    public function get($id){
        $employee = Employee::where('id',$id)->firstOrFail();

        return new EmployeeResource($employee);
        ;
    }
/**
 * @OA\Get(
 *     path="/api/employees",
 *     summary="Retrieve all employees",
 *     tags={"Employees"},
 *     @OA\Parameter(
 *         name="filter",
 *         in="query",
 *         description="Filter employees by name, surname, position, or patronymic",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         description="Page number for paginated results",
 *         required=false,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         description="Number of results per page for paginated results",
 *         required=false,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Employees retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="name",
 *                 type="string",
 *                 example=" jon"
 *             ),
 *              @OA\Property(
 *                 property="surname",
 *                 type="string",
 *                 example=" smith"
 *             ),
 *                @OA\Property(
 *                 property="patronymic",
 *                 type="string",
 *                 example=" jons"
 *             ),
 *              @OA\Property(
 *                 property="birthday",
 *                 type="date",
 *                 example=" 15.02.2020"
 *             ),
 *              @OA\Property(
 *                 property="position",
 *                 type="enum",
 *                 example=" Админ"
 *             ),
 *              @OA\Property(
 *                 property="phone",
 *                 type="string",
 *                 example=" +792665854541"
 *             ),
 *             @OA\Property(
 *                 property="meta",
 *                 type="object",
 *                 @OA\Property(
 *                     property="pagination",

 *                 )
 *             )
 *         )
 *     )
 * )
 */
    public function all(Request $request){
        $employee = QueryBuilder::for(Employee::class)
            ->allowedFilters(['name','surname','position','patronymic'])
            ->get();

        $employee = Employee::paginate(5);

        return  EmployeeResource::collection($employee);

    }
}
